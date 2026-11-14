/**
 * Readowl Bookstore - Client-Side Interactive Data Store for GitHub Pages
 */
const READOWL_DATA = {
    categories: [
        { id: 1, name: "Self Improvement", slug: "self-improvement", count: 4 },
        { id: 2, name: "Technology", slug: "technology", count: 3 },
        { id: 3, name: "Design & UX", slug: "design-ux", count: 3 },
        { id: 4, name: "Fantasy & Fiction", slug: "fantasy-fiction", count: 3 },
        { id: 5, name: "Classic & Philosophy", slug: "classic-philosophy", count: 2 },
        { id: 6, name: "Travel & Photography", slug: "travel-photography", count: 2 }
    ],
    books: [
        {
            id: 1,
            title: "The Year of Less",
            author: "Cait Flanders",
            publisher: "Hay House UK",
            category: "Self Improvement",
            shelf_section: "currently_reading",
            cover_url: "https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80",
            rating: 4.6,
            price: 125000,
            pages: 214,
            reading_progress: 65,
            published_year: 2018,
            stock: 20,
            description: "Kisah nyata perjalanan satu tahun tanpa berbelanja barang konsumtif, menemukan arti kebebasan finansial dan kedamaian batin.",
            synopsis: "Cait Flanders mendokumentasikan eksperimen hidup minimalis di mana ia berhenti membeli pakaian, dekorasi rumah, dan barang konsumtif selama dua belas bulan penuh, menyisakan kebutuhan primer dan refleksi diri mendalam."
        },
        {
            id: 2,
            title: "Clean Architecture",
            author: "Robert C. Martin",
            publisher: "Prentice Hall",
            category: "Technology",
            shelf_section: "currently_reading",
            cover_url: "https://images.unsplash.com/photo-1532012164546-f432f2e3edd4?auto=format&fit=crop&w=600&q=80",
            rating: 4.9,
            price: 210000,
            pages: 432,
            reading_progress: 42,
            published_year: 2017,
            stock: 15,
            description: "Panduan praktis perancangan perangkat lunak yang kokoh, modular, dan mudah dipelihara oleh para pengembang profesional.",
            synopsis: "Paman Bob menjabarkan prinsip-prinsip universal arsitektur sistem software, memisahkan logika domain dari detail framework, database, dan UI demi skalabilitas jangka panjang."
        },
        {
            id: 3,
            title: "Strategic Writing for UX",
            author: "Torrey Podmajersky",
            publisher: "O'Reilly Media",
            category: "Design & UX",
            shelf_section: "currently_reading",
            cover_url: "https://covers.openlibrary.org/b/id/10118805-L.jpg",
            rating: 4.8,
            price: 175000,
            pages: 210,
            reading_progress: 80,
            published_year: 2019,
            stock: 12,
            description: "Strategi menciptakan tulisan antarmuka yang mengarahkan pengguna secara intuitif dan meningkatkan kepuasan interaksi produk digital.",
            synopsis: "Panduan praktis bagi desainer produk dan UX writer dalam menyusun microcopy, alur pesan error, tombol aksi, dan dialog konfirmasi yang mempermudah interaksi pengguna."
        },
        {
            id: 4,
            title: "The Lord of the Rings",
            author: "J.R.R. Tolkien",
            publisher: "George Allen & Unwin",
            category: "Fantasy & Fiction",
            shelf_section: "next_up",
            cover_url: "https://covers.openlibrary.org/b/id/11142995-L.jpg",
            rating: 5.0,
            price: 240000,
            pages: 1178,
            reading_progress: 0,
            published_year: 1954,
            stock: 25,
            description: "Mahakarya fantasi epik mengenai perjuangan persaudaraan pembawa cincin melintasi Middle-earth demi memusnahkan Cincin Utama di Gunung Takdir.",
            synopsis: "Frodo Baggins memulai pengembaraan berbahaya melintasi dataran Mordor didampingi sembilan pembawa cincin untuk menuntaskan takdir dunia dari cengkeraman kegelapan Sauron."
        },
        {
            id: 5,
            title: "The Design of Everyday Things",
            author: "Don Norman",
            publisher: "Basic Books",
            category: "Design & UX",
            shelf_section: "next_up",
            cover_url: "https://covers.openlibrary.org/b/id/7884846-L.jpg",
            rating: 4.8,
            price: 185000,
            pages: 368,
            reading_progress: 0,
            published_year: 2013,
            stock: 16,
            description: "Karya fundamental tentang psikologi desain yang berpusat pada manusia (Human-Centered Design), afordansi, dan umpan balik intuitif.",
            synopsis: "Buku wajib yang membuka mata tentang mengapa pintu sering sulit didorong atau ditarik dan bagaimana rancangan objek di sekitar kita harus memandu intuisi manusia tanpa kebingungan."
        },
        {
            id: 6,
            title: "1984",
            author: "George Orwell",
            publisher: "Secker & Warburg",
            category: "Classic & Philosophy",
            shelf_section: "next_up",
            cover_url: "https://covers.openlibrary.org/b/id/7222246-L.jpg",
            rating: 4.9,
            price: 110000,
            pages: 328,
            reading_progress: 0,
            published_year: 1949,
            stock: 18,
            description: "Novel distopia legendaris yang menggambarkan rezim totalitarian ekstrem, pengawasan massal Big Brother, dan manipulasi kebenaran.",
            synopsis: "Winston Smith bekerja di Kementerian Kebenaran mengubah rekaman sejarah, hingga rasa penasarannya pada kebebasan berpikir menjerumuskannya ke dalam perlawanan bawah tanah."
        },
        {
            id: 7,
            title: "Atomic Habits",
            author: "James Clear",
            publisher: "Avery",
            category: "Self Improvement",
            shelf_section: "finished",
            cover_url: "https://covers.openlibrary.org/b/id/12843460-L.jpg",
            rating: 4.9,
            price: 155000,
            pages: 320,
            reading_progress: 100,
            published_year: 2018,
            stock: 35,
            description: "Cara mudah dan terbukti untuk membangun kebiasaan baik serta mematahkan kebiasaan buruk dengan perbaikan 1% setiap hari.",
            synopsis: "Metodologi berbasis sains perilaku manusia tentang 4 pilar pembentukan kebiasaan: jadikan terlihat, jadikan menarik, jadikan mudah, dan jadikan memuaskan."
        },
        {
            id: 8,
            title: "Steve Jobs",
            author: "Walter Isaacson",
            publisher: "Simon & Schuster",
            category: "Technology",
            shelf_section: "finished",
            cover_url: "https://covers.openlibrary.org/b/id/8231856-L.jpg",
            rating: 4.9,
            price: 195000,
            pages: 656,
            reading_progress: 100,
            published_year: 2011,
            stock: 14,
            description: "Biografi komprehensif pendiri Apple dengan visi estetika, inovasi revolusioner, dan ketegasan dalam menciptakan produk dunia.",
            synopsis: "Kisah mendalam sosok visioner yang merevolusi enam industri berbeda: komputer pribadi, film animasi, musik, telepon seluler, komputasi tablet, dan penerbitan digital."
        },
        {
            id: 9,
            title: "Epic Bike Rides of the World",
            author: "Lonely Planet",
            publisher: "Lonely Planet Global",
            category: "Travel & Photography",
            shelf_section: "next_up",
            cover_url: "https://images.unsplash.com/photo-1488646953014-85cb44e25828?auto=format&fit=crop&w=600&q=80",
            rating: 4.8,
            price: 220000,
            pages: 320,
            reading_progress: 0,
            published_year: 2016,
            stock: 14,
            description: "Kompilasi 200 rute bersepeda paling menakjubkan di sekeliling bumi, lengkap dengan tips perjalanan dan lanskap menawan.",
            synopsis: "Rangkuman visual dan panduan logistik rute kayuhan terbaik melintasi pegunungan Alpen, pesisir Pasifik, hingga jalan setapak pedesaan Jepang."
        },
        {
            id: 10,
            title: "Meditations",
            author: "Marcus Aurelius",
            publisher: "Penguin Classics",
            category: "Classic & Philosophy",
            shelf_section: "finished",
            cover_url: "https://covers.openlibrary.org/b/id/8739161-L.jpg",
            rating: 4.8,
            price: 105000,
            pages: 256,
            reading_progress: 100,
            published_year: 2006,
            stock: 22,
            description: "Catatan harian kaisar filsuf Romawi mengenai ketenangan batin, stoikisme, disiplin moral, dan cara menghadapi gejolak hidup.",
            synopsis: "Ajaran stoikisme murni langsung dari catatan pribadi Marcus Aurelius saat memimpin kekaisaran di medan perang, mengingatkan tentang kefanaan dan penguasaan ego."
        }
    ],

    formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(amount);
    },

    getCart() {
        try {
            const raw = localStorage.getItem('readowl_cart');
            if (raw) return JSON.parse(raw);
        } catch (e) {
            console.error('Error loading cart', e);
        }
        // Initial default items for demonstration
        const defaultCart = [
            { id: 1, book_id: 1, title: "The Year of Less", author: "Cait Flanders", price: 125000, cover_url: "https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80", stock: 20, quantity: 1, selected: true },
            { id: 2, book_id: 2, title: "Clean Architecture", author: "Robert C. Martin", price: 210000, cover_url: "https://images.unsplash.com/photo-1532012164546-f432f2e3edd4?auto=format&fit=crop&w=600&q=80", stock: 15, quantity: 1, selected: true }
        ];
        this.saveCart(defaultCart);
        return defaultCart;
    },

    saveCart(cart) {
        try {
            localStorage.setItem('readowl_cart', JSON.stringify(cart));
            window.dispatchEvent(new CustomEvent('cart-updated', { detail: cart }));
        } catch (e) {
            console.error('Error saving cart', e);
        }
    },

    addToCart(book, quantity = 1) {
        const cart = this.getCart();
        const existing = cart.find(item => item.book_id === book.id);
        if (existing) {
            existing.quantity = Math.min(book.stock, existing.quantity + quantity);
        } else {
            cart.push({
                id: Date.now(),
                book_id: book.id,
                title: book.title,
                author: book.author,
                price: book.price,
                cover_url: book.cover_url,
                stock: book.stock,
                quantity: Math.min(book.stock, quantity),
                selected: true
            });
        }
        this.saveCart(cart);
        this.showToast(`"${book.title}" berhasil ditambahkan ke keranjang!`);
    },

    showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-5 right-5 z-50 flex items-center gap-3 px-5 py-3 rounded-xl shadow-xl text-sm font-medium transition-all duration-300 transform translate-y-4 opacity-0 ${
            type === 'success' ? 'bg-[#3D2719] text-white border border-[#B0825B]' : 'bg-red-700 text-white'
        }`;
        toast.innerHTML = `
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
            <span>${message}</span>
        `;
        document.body.appendChild(toast);
        requestAnimationFrame(() => {
            toast.classList.remove('translate-y-4', 'opacity-0');
        });
        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-4');
            setTimeout(() => toast.remove(), 400);
        }, 3000);
    }
};
