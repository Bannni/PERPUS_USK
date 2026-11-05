<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::truncate();

        $books = [
            // Currently Reading (3 books matching top shelf)
            [
                'title' => 'The Year of Less',
                'author' => 'Cait Flanders',
                'category' => 'Self Improvement',
                'shelf_section' => 'currently_reading',
                'cover_url' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80',
                'rating' => 4.6,
                'price' => 125000,
                'pages' => 214,
                'reading_progress' => 65,
                'synopsis' => 'Kisah nyata perjalanan satu tahun tanpa berbelanja barang konsumtif, menemukan arti kebebasan finansial dan kedamaian batin.',
                'published_year' => 2018,
            ],
            [
                'title' => 'Clean Architecture',
                'author' => 'Robert C. Martin',
                'category' => 'Technology',
                'shelf_section' => 'currently_reading',
                'cover_url' => 'https://images.unsplash.com/photo-1532012164546-f432f2e3edd4?auto=format&fit=crop&w=600&q=80',
                'rating' => 4.9,
                'price' => 210000,
                'pages' => 432,
                'reading_progress' => 42,
                'synopsis' => 'Panduan praktis perancangan perangkat lunak yang kokoh, modular, dan mudah dipelihara oleh para pengembang profesional.',
                'published_year' => 2017,
            ],
            [
                'title' => 'Strategic Writing for UX',
                'author' => 'Torrey Podmajersky',
                'category' => 'Design & UX',
                'shelf_section' => 'currently_reading',
                'cover_url' => 'https://covers.openlibrary.org/b/id/10118805-L.jpg',
                'rating' => 4.8,
                'price' => 175000,
                'pages' => 210,
                'reading_progress' => 80,
                'synopsis' => 'Strategi menciptakan tulisan antarmuka yang mengarahkan pengguna secara intuitif dan meningkatkan kepuasan interaksi produk digital.',
                'published_year' => 2019,
            ],

            // Next Up (6 books matching middle shelf)
            [
                'title' => 'Lietuvos Paukščiai',
                'author' => 'Selemonas Paltanavičius',
                'category' => 'Nature & Wildlife',
                'shelf_section' => 'next_up',
                'cover_url' => 'https://images.unsplash.com/photo-1516205651411-aef33a44f7c2?auto=format&fit=crop&w=600&q=80',
                'rating' => 4.7,
                'price' => 140000,
                'pages' => 320,
                'reading_progress' => 0,
                'synopsis' => 'Ensiklopedia visual keanekaragaman burung di alam liar Eropa Utara dengan ilustrasi fotografi habitat yang memukau.',
                'published_year' => 2020,
            ],
            [
                'title' => 'The Lord of the Rings',
                'author' => 'J.R.R. Tolkien',
                'category' => 'Fantasy',
                'shelf_section' => 'next_up',
                'cover_url' => 'https://covers.openlibrary.org/b/id/12818862-L.jpg',
                'rating' => 5.0,
                'price' => 260000,
                'pages' => 1178,
                'reading_progress' => 0,
                'synopsis' => 'Kisah kepahlawanan epik di Dunia Tengah untuk menghancurkan Cincin Utama dan membebaskan dunia dari kegelapan Penguasa Kegelapan Sauron.',
                'published_year' => 1954,
            ],
            [
                'title' => 'Epic Bike Rides of the World',
                'author' => 'Lonely Planet',
                'category' => 'Travel & Adventure',
                'shelf_section' => 'next_up',
                'cover_url' => 'https://covers.openlibrary.org/b/id/11149721-L.jpg',
                'rating' => 4.8,
                'price' => 220000,
                'pages' => 320,
                'reading_progress' => 0,
                'synopsis' => 'Jelajahi 200 rute bersepeda paling menakjubkan di dunia dari pegunungan Alpen hingga gurun Patagonia.',
                'published_year' => 2016,
            ],
            [
                'title' => 'Mythos: The Greek Myths Retold',
                'author' => 'Stephen Fry',
                'category' => 'Mythology & Literature',
                'shelf_section' => 'next_up',
                'cover_url' => 'https://covers.openlibrary.org/b/id/8314131-L.jpg',
                'rating' => 4.7,
                'price' => 165000,
                'pages' => 448,
                'reading_progress' => 0,
                'synopsis' => 'Penceritaan kembali mitologi Yunani kuno dengan sentuhan humor cerdas, narasi segar, dan wawasan mendalam.',
                'published_year' => 2017,
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'category' => 'Classic Fiction',
                'shelf_section' => 'next_up',
                'cover_url' => 'https://covers.openlibrary.org/b/id/8575787-L.jpg',
                'rating' => 4.9,
                'price' => 95000,
                'pages' => 328,
                'reading_progress' => 0,
                'synopsis' => 'Distopia legendaris tentang totalitarianisme, pengawasan massa Big Brother, dan perjuangan individu mempertahankan kebebasan berpikir.',
                'published_year' => 1949,
            ],
            [
                'title' => 'The Design of Everyday Things',
                'author' => 'Don Norman',
                'category' => 'Design & UX',
                'shelf_section' => 'next_up',
                'cover_url' => 'https://covers.openlibrary.org/b/id/10523455-L.jpg',
                'rating' => 4.9,
                'price' => 185000,
                'pages' => 368,
                'reading_progress' => 0,
                'synopsis' => 'Karya fundamental tentang pentingnya desain yang berpusat pada manusia, menjelaskan mengapa pintu dan saklar sering membuat kita bingung.',
                'published_year' => 1988,
            ],

            // Finished (6 books matching bottom shelf)
            [
                'title' => 'Steve Jobs',
                'author' => 'Walter Isaacson',
                'category' => 'Biography',
                'shelf_section' => 'finished',
                'cover_url' => 'https://covers.openlibrary.org/b/id/8431356-L.jpg',
                'rating' => 4.8,
                'price' => 195000,
                'pages' => 656,
                'reading_progress' => 100,
                'synopsis' => 'Biografi komprehensif pendiri Apple, merekam revolusi teknologi personal komputer, animasi digital, musik, dan ponsel pintar.',
                'published_year' => 2011,
            ],
            [
                'title' => 'The Geometry of Holding Hands',
                'author' => 'Alexander McCall Smith',
                'category' => 'Literary Fiction',
                'shelf_section' => 'finished',
                'cover_url' => 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&w=600&q=80',
                'rating' => 4.5,
                'price' => 135000,
                'pages' => 256,
                'reading_progress' => 100,
                'synopsis' => 'Kisah filosofis hangat tentang hubungan antarmanusia, moralitas, dan kebijaksanaan hidup sehari-hari di Edinburgh.',
                'published_year' => 2020,
            ],
            [
                'title' => 'One Year on a Bike',
                'author' => 'Martijn Doolaard',
                'category' => 'Photography & Travel',
                'shelf_section' => 'finished',
                'cover_url' => 'https://images.unsplash.com/photo-1476820865390-c52aeebb9891?auto=format&fit=crop&w=600&q=80',
                'rating' => 4.9,
                'price' => 280000,
                'pages' => 368,
                'reading_progress' => 100,
                'synopsis' => 'Jurnal visual perjalanan bersepeda sendirian dari Amsterdam menuju Singapura sejauh 17.000 kilometer melintasi pegunungan dan gurun.',
                'published_year' => 2017,
            ],
            [
                'title' => '101 Things to Do in Switzerland',
                'author' => 'Swiss Alpine Travel',
                'category' => 'Travel Guide',
                'shelf_section' => 'finished',
                'cover_url' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=600&q=80',
                'rating' => 4.6,
                'price' => 150000,
                'pages' => 204,
                'reading_progress' => 100,
                'synopsis' => 'Panduan lengkap berlibur ke Swiss, mulai dari danau kristal, jalur kereta gantung tertinggi, desa tradisional, hingga kuliner khas cokelat.',
                'published_year' => 2021,
            ],
            [
                'title' => 'The Hobbit',
                'author' => 'J.R.R. Tolkien',
                'category' => 'Fantasy',
                'shelf_section' => 'finished',
                'cover_url' => 'https://covers.openlibrary.org/b/id/8406786-L.jpg',
                'rating' => 4.9,
                'price' => 130000,
                'pages' => 310,
                'reading_progress' => 100,
                'synopsis' => 'Petualangan Bilbo Baggins bersama para Kurcaci dan penyihir Gandalf untuk merebut kembali harta karun Gunung Sunyi dari naga Smaug.',
                'published_year' => 1937,
            ],
            [
                'title' => 'Logo Design Love',
                'author' => 'David Airey',
                'category' => 'Design & Branding',
                'shelf_section' => 'finished',
                'cover_url' => 'https://covers.openlibrary.org/b/id/7222246-L.jpg',
                'rating' => 4.8,
                'price' => 170000,
                'pages' => 216,
                'reading_progress' => 100,
                'synopsis' => 'Buku panduan mendalam tentang proses penciptaan identitas merek yang ikonik, efektif, dan tak lekang oleh waktu.',
                'published_year' => 2014,
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
