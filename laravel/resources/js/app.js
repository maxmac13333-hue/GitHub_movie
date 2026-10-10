import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('seatBooking', (url, initialTaken) => ({
    selected: [], taken: initialTaken, message: '', timer: null,
    get total() { return this.selected.reduce((total, seat) => total + seat.price, 0); },
    init() { this.timer = setInterval(() => this.refresh(), 15000); },
    destroy() { clearInterval(this.timer); },
    toggle(data) {
        const id = Number(data.id);
        if (this.taken.includes(id)) return;
        if (this.selected.some(seat => seat.id === id)) return this.remove(id);
        if (this.selected.length >= 8) { this.message = 'เลือกได้สูงสุด 8 ที่นั่งต่อรายการ'; return; }
        this.message = '';
        this.selected.push({id, no: data.no, price: Number(data.price)});
    },
    remove(id) { this.selected = this.selected.filter(seat => seat.id !== id); this.message = ''; },
    async refresh() {
        try {
            const response = await fetch(url, {headers: {'Accept': 'application/json'}, cache: 'no-store'});
            if (!response.ok) return;
            const data = await response.json();
            this.taken = data.taken;
            if (this.selected.some(seat => this.taken.includes(seat.id))) {
                this.selected = this.selected.filter(seat => !this.taken.includes(seat.id));
                this.message = 'ที่นั่งบางส่วนถูกจองแล้ว กรุณาเลือกที่นั่งใหม่';
            }
        } catch (_) { /* Final confirmation checks availability again on the server. */ }
    },
}));

Alpine.start();
