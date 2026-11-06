<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Stock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Stock::truncate();
        Book::truncate();
        Category::truncate();
        Contact::truncate();
        Schema::enableForeignKeyConstraints();

        // 1. Seed Categories (BNSP Spec: categories table)
        $categoriesData = [
            'Self Improvement' => 'Buku pengembangan diri, produktivitas, dan psikologi praktis.',
            'Technology' => 'Buku pemrograman, arsitektur sistem, dan inovasi kecerdasan buatan.',
            'Design & UX' => 'Koleksi estetika desain visual, antarmuka produk digital, dan tipografi.',
            'Fantasy & Fiction' => 'Karya sastra klasik, dunia imajinatif, dan epik fantasi.',
            'Classic & Philosophy' => 'Karya pemikiran abadi, filsafat kehidupan, dan esai sastra.',
            'Travel & Photography' => 'Jurnal penjelajahan alam, dokumenter fotografi, dan budaya dunia.',
        ];

        $categoryMap = [];
        foreach ($categoriesData as $name => $desc) {
            $cat = Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
            $categoryMap[$name] = $cat->id;
        }

        // 2. Seed Books with publishers, stock, description
        $books = [
            [
                'title' => 'The Year of Less',
                'author' => 'Cait Flanders',
                'publisher' => 'Hay House UK',
                'category' => 'Self Improvement',
                'shelf_section' => 'currently_reading',
                'cover_url' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80',
                'rating' => 4.6,
                'price' => 125000,
                'pages' => 214,
                'reading_progress' => 65,
                'description' => 'Kisah nyata perjalanan satu tahun tanpa berbelanja barang konsumtif, menemukan arti kebebasan finansial dan kedamaian batin.',
                'synopsis' => 'Kisah nyata perjalanan satu tahun tanpa berbelanja barang konsumtif, menemukan arti kebebasan finansial dan kedamaian batin.',
                'published_year' => 2018,
                'stock' => 20,
            ],
            [
                'title' => 'Clean Architecture',
                'author' => 'Robert C. Martin',
                'publisher' => 'Prentice Hall',
                'category' => 'Technology',
                'shelf_section' => 'currently_reading',
                'cover_url' => 'https://images.unsplash.com/photo-1532012164546-f432f2e3edd4?auto=format&fit=crop&w=600&q=80',
                'rating' => 4.9,
                'price' => 210000,
                'pages' => 432,
                'reading_progress' => 42,
                'description' => 'Panduan praktis perancangan perangkat lunak yang kokoh, modular, dan mudah dipelihara oleh para pengembang profesional.',
                'synopsis' => 'Panduan praktis perancangan perangkat lunak yang kokoh, modular, dan mudah dipelihara oleh para pengembang profesional.',
                'published_year' => 2017,
                'stock' => 15,
            ],
            [
                'title' => 'Strategic Writing for UX',
                'author' => 'Torrey Podmajersky',
                'publisher' => "O'Reilly Media",
                'category' => 'Design & UX',
                'shelf_section' => 'currently_reading',
                'cover_url' => 'https://covers.openlibrary.org/b/id/10118805-L.jpg',
                'rating' => 4.8,
                'price' => 175000,
                'pages' => 210,
                'reading_progress' => 80,
                'description' => 'Strategi menciptakan tulisan antarmuka yang mengarahkan pengguna secara intuitif dan meningkatkan kepuasan interaksi produk digital.',
                'synopsis' => 'Strategi menciptakan tulisan antarmuka yang mengarahkan pengguna secara intuitif dan meningkatkan kepuasan interaksi produk digital.',
                'published_year' => 2019,
                'stock' => 12,
            ],
            [
                'title' => 'Lietuvos Paukščiai',
                'author' => 'Vytautas Logminas',
                'publisher' => 'Mokslas Press',
                'category' => 'Travel & Photography',
                'shelf_section' => 'next_up',
                'cover_url' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=600&q=80',
                'rating' => 4.7,
                'price' => 160000,
                'pages' => 312,
                'reading_progress' => 0,
                'description' => 'Ensiklopedi ornitologi dan dokumentasi visual keanekaragaman burung liar di habitat alami Eropa Timur.',
                'synopsis' => 'Ensiklopedi ornitologi dan dokumentasi visual keanekaragaman burung liar di habitat alami Eropa Timur.',
                'published_year' => 2015,
                'stock' => 8,
            ],
            [
                'title' => 'The Lord of the Rings',
                'author' => 'J.R.R. Tolkien',
                'publisher' => 'George Allen & Unwin',
                'category' => 'Fantasy & Fiction',
                'shelf_section' => 'next_up',
                'cover_url' => 'https://covers.openlibrary.org/b/id/11142995-L.jpg',
                'rating' => 5.0,
                'price' => 240000,
                'pages' => 1178,
                'reading_progress' => 0,
                'description' => 'Mahakarya fantasi epik mengenai perjuangan persaudaraan pembawa cincin melintasi Middle-earth demi memusnahkan Cincin Utama di Gunung Takdir.',
                'synopsis' => 'Mahakarya fantasi epik mengenai perjuangan persaudaraan pembawa cincin melintasi Middle-earth demi memusnahkan Cincin Utama di Gunung Takdir.',
                'published_year' => 1954,
                'stock' => 25,
            ],
            [
                'title' => 'Epic Bike Rides of the World',
                'author' => 'Lonely Planet',
                'publisher' => 'Lonely Planet Global',
                'category' => 'Travel & Photography',
                'shelf_section' => 'next_up',
                'cover_url' => 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?auto=format&fit=crop&w=600&q=80',
                'rating' => 4.8,
                'price' => 220000,
                'pages' => 320,
                'reading_progress' => 0,
                'description' => 'Kompilasi 200 rute bersepeda paling menakjubkan di sekeliling bumi, lengkap dengan tips perjalanan dan lanskap menawan.',
                'synopsis' => 'Kompilasi 200 rute bersepeda paling menakjubkan di sekeliling bumi, lengkap dengan tips perjalanan dan lanskap menawan.',
                'published_year' => 2016,
                'stock' => 14,
            ],
            [
                'title' => 'Little Red Riding Hood',
                'author' => 'Brothers Grimm',
                'publisher' => 'Penguin Classics',
                'category' => 'Fantasy & Fiction',
                'shelf_section' => 'next_up',
                'cover_url' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=600&q=80',
                'rating' => 4.4,
                'price' => 85000,
                'pages' => 96,
                'reading_progress' => 0,
                'description' => 'Kisah dongeng klasik Eropa tentang kewaspadaan, perjalanan di tengah rimba, dan pertemuan dengan sang serigala licik.',
                'synopsis' => 'Kisah dongeng klasik Eropa tentang kewaspadaan, perjalanan di tengah rimba, dan pertemuan dengan sang serigala licik.',
                'published_year' => 1812,
                'stock' => 30,
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'publisher' => 'Secker & Warburg',
                'category' => 'Classic & Philosophy',
                'shelf_section' => 'next_up',
                'cover_url' => 'https://covers.openlibrary.org/b/id/7222246-L.jpg',
                'rating' => 4.9,
                'price' => 110000,
                'pages' => 328,
                'reading_progress' => 0,
                'description' => 'Novel distopia legendaris yang menggambarkan rezim totalitarian ekstrem, pengawasan massal Big Brother, dan manipulasi kebenaran.',
                'synopsis' => 'Novel distopia legendaris yang menggambarkan rezim totalitarian ekstrem, pengawasan massal Big Brother, dan manipulasi kebenaran.',
                'published_year' => 1949,
                'stock' => 18,
            ],
            [
                'title' => 'The Design of Everyday Things',
                'author' => 'Don Norman',
                'publisher' => 'Basic Books',
                'category' => 'Design & UX',
                'shelf_section' => 'next_up',
                'cover_url' => 'https://covers.openlibrary.org/b/id/7884846-L.jpg',
                'rating' => 4.8,
                'price' => 185000,
                'pages' => 368,
                'reading_progress' => 0,
                'description' => 'Karya fundamental tentang psikologi desain yang berpusat pada manusia (Human-Centered Design), afordansi, dan umpan balik intuitif.',
                'synopsis' => 'Karya fundamental tentang psikologi desain yang berpusat pada manusia (Human-Centered Design), afordansi, dan umpan balik intuitif.',
                'published_year' => 2013,
                'stock' => 16,
            ],
            [
                'title' => 'Steve Jobs',
                'author' => 'Walter Isaacson',
                'publisher' => 'Simon & Schuster',
                'category' => 'Technology',
                'shelf_section' => 'finished',
                'cover_url' => 'https://covers.openlibrary.org/b/id/8231856-L.jpg',
                'rating' => 4.9,
                'price' => 195000,
                'pages' => 656,
                'reading_progress' => 100,
                'description' => 'Biografi komprehensif pendiri Apple, merekam revolusi teknologi personal komputer, animasi digital, musik, dan ponsel pintar.',
                'synopsis' => 'Biografi komprehensif pendiri Apple, merekam revolusi teknologi personal komputer, animasi digital, musik, dan ponsel pintar.',
                'published_year' => 2011,
                'stock' => 10,
            ],
            [
                'title' => 'The Hobbit',
                'author' => 'J.R.R. Tolkien',
                'publisher' => 'HarperCollins',
                'category' => 'Fantasy & Fiction',
                'shelf_section' => 'finished',
                'cover_url' => 'https://covers.openlibrary.org/b/id/8406786-L.jpg',
                'rating' => 4.9,
                'price' => 130000,
                'pages' => 310,
                'reading_progress' => 100,
                'description' => 'Petualangan Bilbo Baggins bersama para Kurcaci dan penyihir Gandalf untuk merebut kembali harta karun Gunung Sunyi dari naga Smaug.',
                'synopsis' => 'Petualangan Bilbo Baggins bersama para Kurcaci dan penyihir Gandalf untuk merebut kembali harta karun Gunung Sunyi dari naga Smaug.',
                'published_year' => 1937,
                'stock' => 22,
            ],
            [
                'title' => 'Logo Design Love',
                'author' => 'David Airey',
                'publisher' => 'Peachpit Press',
                'category' => 'Design & UX',
                'shelf_section' => 'finished',
                'cover_url' => 'https://covers.openlibrary.org/b/id/7222246-L.jpg',
                'rating' => 4.8,
                'price' => 170000,
                'pages' => 216,
                'reading_progress' => 100,
                'description' => 'Buku panduan mendalam tentang proses penciptaan identitas merek yang ikonik, efektif, dan tak lekang oleh waktu.',
                'synopsis' => 'Buku panduan mendalam tentang proses penciptaan identitas merek yang ikonik, efektif, dan tak lekang oleh waktu.',
                'published_year' => 2014,
                'stock' => 15,
            ],
        ];

        foreach ($books as $data) {
            $stockQty = $data['stock'] ?? 15;
            unset($data['stock']);

            $catName = $data['category'];
            $data['category_id'] = $categoryMap[$catName] ?? null;
            $data['cover_image'] = $data['cover_url'];

            $book = Book::create($data);

            // Seed stock record (BNSP requirement)
            Stock::updateOrCreate(
                ['book_id' => $book->id],
                ['quantity' => $stockQty]
            );
        }

        // 3. Seed demo message in contacts table for BNSP demonstration
        Contact::create([
            'name' => 'Budi Santoso (Calon Pembaca)',
            'email' => 'budi.santoso@gmail.com',
            'message' => 'Halo Admin Readowl! Apakah buku Clean Architecture cetakan terbaru edisi bahasa Indonesia akan segera restock?',
            'is_read' => false,
        ]);
    }
}
