(function () {
    const STORAGE_KEY = 'sw_sale_cart';
    const CUSTOMER_KEY = 'sw_sale_customer_id';
    const token = () => document.querySelector('meta[name="csrf-token"]')?.content || '';

    function readCart() {
        try {
            const rows = JSON.parse(sessionStorage.getItem(STORAGE_KEY) || '[]');
            return Array.isArray(rows) ? rows : [];
        } catch (_) {
            return [];
        }
    }

    function writeCart(lines) {
        sessionStorage.setItem(STORAGE_KEY, JSON.stringify(lines || []));
    }

    function money(value) {
        return Number(value || 0).toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 2 });
    }

    async function postJson(url, payload) {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token(),
            },
            body: JSON.stringify(payload),
        });
        const data = await response.json().catch(() => ({}));
        if (!response.ok) {
            throw new Error(Object.values(data.errors || {}).flat().join(' ') || data.message || 'Request failed');
        }
        return data;
    }

    document.addEventListener('alpine:init', () => {
        Alpine.data('saleCheckout', (config) => ({
            products: config.products || [],
            searchUrl: config.searchUrl,
            paymentUrl: config.paymentUrl,
            submitUrl: config.submitUrl,
            quoteUrl: config.quoteUrl,
            barcodeUrl: config.barcodeUrl,
            shiftOpen: !!config.shiftOpen,
            query: config.query || '',
            lines: [],
            error: '',
            busy: false,
            initCheckout() {
                this.lines = readCart();
                this.refreshQuote();
            },
            money,
            quantityFor(productId, variantId = null) {
                return this.lines.filter((line) => Number(line.product_id) === Number(productId) && ((line.variant_id ?? null) === (variantId ?? null))).reduce((sum, line) => sum + Number(line.quantity || 0), 0);
            },
            subtotal() {
                return this.lines.reduce((sum, line) => sum + (Number(line.quantity) * this.unitNet(line)), 0);
            },
            total() {
                return this.subtotal();
            },
            unitNet(line) {
                const price = Number(line.price || 0);
                const discount = Math.min(price, Number(line.manual_discount || 0));
                return Math.max(0, price - discount);
            },
            lineTotal(line) {
                return this.unitNet(line) * Number(line.quantity || 0);
            },
            persist() {
                writeCart(this.lines);
                this.refreshQuote();
            },
            async refreshQuote() {
                this.error = '';
                if (!this.lines.length) return;
                try {
                    await postJson(this.quoteUrl, this.payload('hold'));
                } catch (error) {
                    this.error = error.message;
                }
            },
            payload(action) {
                return {
                    action,
                    lines: this.lines.map((line) => ({
                        product_id: Number(line.product_id),
                        variant_id: line.variant_id ?? null,
                        quantity: Number(line.quantity),
                        manual_discount: Number(line.manual_discount || 0),
                        manual_discount_percent: 0,
                    })),
                    customer_id: sessionStorage.getItem(CUSTOMER_KEY) || null,
                    payment_method: 'cash',
                    amount_tendered: this.total(),
                };
            },
            add(product) {
                this.error = '';
                const line = this.lines.find((item) => Number(item.product_id) === Number(product.id) && ((item.variant_id ?? null) === (product.variant_id ?? null)));
                const available = Number(product.stock || 0);
                if (line) {
                    if (Number(line.quantity) + 1 > available) {
                        this.error = available <= 0 ? 'This product is out of stock.' : 'Only ' + available + ' available.';
                        return;
                    }
                    line.quantity = Number(line.quantity) + 1;
                } else {
                    if (available < 1) {
                        this.error = 'This product is out of stock.';
                        return;
                    }
                    this.lines.push({
                        product_id: Number(product.id),
                        variant_id: product.variant_id ?? null,
                        name: product.name,
                        price: Number(product.price || 0),
                        stock: available,
                        tax_type: product.tax_type,
                        quantity: 1,
                        manual_discount: 0,
                    });
                }
                this.persist();
            },
            change(index, delta) {
                const line = this.lines[index];
                if (!line) return;
                const next = Number(line.quantity) + Number(delta);
                if (next <= 0) {
                    this.lines.splice(index, 1);
                    this.persist();
                    return;
                }
                if (next > Number(line.stock || 0)) {
                    this.error = 'Only ' + Number(line.stock || 0) + ' available.';
                    return;
                }
                line.quantity = next;
                this.persist();
            },
            remove(index) {
                this.lines.splice(index, 1);
                this.persist();
            },
            editDiscount(index) {
                const line = this.lines[index];
                if (!line) return;
                const current = String(line.manual_discount || 0);
                const value = window.prompt('Discount per unit', current);
                if (value === null) return;
                const parsed = Math.max(0, Math.min(Number(line.price || 0), Number(value || 0)));
                if (!Number.isFinite(parsed)) return;
                line.manual_discount = parsed;
                this.persist();
            },
            clear() {
                this.lines = [];
                sessionStorage.removeItem(CUSTOMER_KEY);
                writeCart([]);
                this.error = '';
            },
            async hold() {
                if (this.busy || !this.lines.length) return;
                this.busy = true;
                this.error = '';
                try {
                    const result = await postJson(this.submitUrl, this.payload('hold'));
                    this.clear();
                    window.location.assign(result.redirect);
                } catch (error) {
                    this.error = error.message;
                } finally {
                    this.busy = false;
                }
            },
            goPayment() {
                if (!this.lines.length) return;
                window.location.assign(this.paymentUrl);
            },
            async submitSearch() {
                const code = String(this.query || '').trim();
                if (!code) return this.liveSearch();
                try {
                    const response = await fetch(this.barcodeUrl + '?code=' + encodeURIComponent(code), { headers: { Accept: 'application/json' } });
                    if (response.ok) {
                        const product = await response.json();
                        this.add(product);
                        this.query = '';
                        await this.liveSearch();
                        return;
                    }
                } catch (_) {}
                await this.liveSearch();
            },
            async liveSearch() {
                const url = new URL(this.searchUrl, window.location.origin);
                if (String(this.query || '').trim() !== '') url.searchParams.set('q', String(this.query).trim());
                await this.loadCatalog(url.toString());
            },
            async navigatePage(event) {
                await this.loadCatalog(event.currentTarget.href);
            },
            async loadCatalog(url) {
                try {
                    const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    if (!response.ok) throw new Error('Unable to search products');
                    const html = await response.text();
                    const page = new DOMParser().parseFromString(html, 'text/html');
                    const next = page.querySelector('#sw-catalog-results');
                    const current = document.querySelector('#sw-catalog-results');
                    if (!next || !current) throw new Error('Product results unavailable');
                    current.innerHTML = next.innerHTML;
                    Alpine.initTree(current);
                    window.history.replaceState({}, '', url);
                } catch (error) {
                    this.error = error.message;
                }
            },
        }));

        Alpine.data('salePayment', (config) => ({
            submitUrl: config.submitUrl, quoteUrl: config.quoteUrl, backUrl: config.backUrl,
            customers: config.customers || [], shiftOpen: !!config.shiftOpen,
            lines: [], serverQuote: null, paymentMethod: 'cash', tendered: '', paymentReference: '',
            customerId: sessionStorage.getItem(CUSTOMER_KEY) || '', customerSearch: '',
            overLimitApproved: false, error: '', busy: false,
            async initPayment() {
                this.lines = readCart();
                const c = this.customers.find(x => String(x.id) === String(this.customerId));
                if (c) this.customerSearch = this.customerLabel(c);
                if (this.lines.length) {
                    await this.refreshQuote();
                    this.tendered = this.total().toFixed(2);
                }
            },
            money, customerLabel(c) { return [c.first_name,c.last_name].filter(Boolean).join(' ') + (c.phone ? ' • '+c.phone : ''); },
            pickCustomerFromSearch() {
                const c=this.customers.find(x=>this.customerLabel(x)===this.customerSearch.trim());
                this.customerId=c?c.id:'';
                if(this.customerId) sessionStorage.setItem(CUSTOMER_KEY,String(this.customerId));
                else sessionStorage.removeItem(CUSTOMER_KEY);
                this.refreshQuote();
            },
            selectedCustomer() { return this.customers.find(c=>String(c.id)===String(this.customerId)); },
            creditOverLimit() {
                const c=this.selectedCustomer();
                return !!c && c.credit_limit!==null && c.credit_limit!==undefined && Number(c.credit_balance||0)+this.creditDue()>Number(c.credit_limit);
            },
            unitNet(line) {return Math.max(0,Number(line.price||0)-Math.min(Number(line.price||0),Number(line.manual_discount||0)));},
            lineTotal(line) {return this.unitNet(line)*Number(line.quantity||0);},
            subtotal() {return this.lines.reduce((s,l)=>s+this.lineTotal(l),0);},
            total() {return this.serverQuote?Number(this.serverQuote.total):this.subtotal();},
            rendered() {return Math.max(0,Number(this.tendered)||0);},
            applied() {return Math.min(this.total(),this.rendered());},
            creditDue() {return Math.max(0,this.total()-this.applied());},
            changeDue() {return this.paymentMethod==='cash'?Math.max(0,this.rendered()-this.total()):0;},
            payload(action='complete') {return {action,lines:this.lines.map(l=>({product_id:Number(l.product_id),variant_id:l.variant_id??null,quantity:Number(l.quantity),manual_discount:Number(l.manual_discount||0),manual_discount_percent:Number(l.manual_discount_percent||0)})),customer_id:this.customerId||null,held_sale_id:sessionStorage.getItem('sw_held_sale_id')||null,payment_method:this.paymentMethod,amount_tendered:this.rendered(),payment_reference:this.paymentReference||null,over_limit_approved:this.overLimitApproved?1:0};},
            async refreshQuote() {
                this.serverQuote=null;if(!this.lines.length)return;
                try{this.serverQuote=await postJson(this.quoteUrl,{...this.payload('hold')});}
                catch(e){this.error=e.message;}
            },
            async complete() {
                if(this.busy||!this.lines.length)return;
                this.pickCustomerFromSearch();
                if(this.creditDue()>0&&!this.customerId){this.error='Select a customer for the outstanding credit.';return;}
                if(this.paymentMethod==='mpesa'){this.error='M-Pesa verification is not connected.';return;}
                if(this.paymentMethod==='card'&&this.applied()>0&&!this.paymentReference.trim()){this.error='Enter the card terminal reference.';return;}
                if(this.creditOverLimit()&&!this.overLimitApproved){this.error='Credit limit exceeded. Manager approval is required.';return;}
                this.busy=true;this.error='';
                try {const result=await postJson(this.submitUrl,this.payload());writeCart([]);sessionStorage.removeItem(CUSTOMER_KEY);sessionStorage.removeItem('sw_held_sale_id');window.location.assign(result.redirect);}
                catch(e){this.error=e.message;}
                finally{this.busy=false;}
            },
        }));
    });
})();
