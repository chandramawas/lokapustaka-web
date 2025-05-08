<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Genre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $drama = Genre::firstOrCreate(['name' => 'Drama']);
        $thriller = Genre::firstOrCreate(['name' => 'Thriller']);
        $sejarah = Genre::firstOrCreate(['name' => 'Sejarah']);
        $fantasi = Genre::firstOrCreate(['name' => 'Fantasi']);
        $motivasi = Genre::firstOrCreate(['name' => 'Motivasi']);
        $sains = Genre::firstOrCreate(['name' => 'Sains']);
        $fiksiIlmiah = Genre::firstOrCreate(['name' => 'Fiksi Ilmiah']);
        $teknologi = Genre::firstOrCreate(['name' => 'Teknologi']);
        $pendidikan = Genre::firstOrCreate(['name' => 'Pendidikan']);
        $komik = Genre::firstOrCreate(['name' => 'Komik']);
        $psikologi = Genre::firstOrCreate(['name' => 'Psikologi']);
        $fiksiKlasik = Genre::firstOrCreate(['name' => 'Fiksi Klasik']);

        $book1 = Book::create([
            'title' => 'Sejarah Dunia yang Disembunyikan',
            'epub_path' => 'epubs/1_Pride-and-Prejudice_Austen_1813.epub',
            'author' => 'Jonathan Black',
            'publisher' => 'Gramedia',
            'year' => '2013',
            'isbn' => '9786020328615',
            'pages' => 480,
            'language' => 'Indonesia',
            'description' => 'Buku ini adalah panduan anti-mainstream tentang bagaimana menjalani hidup yang lebih bermakna dengan tidak peduli terhadap hal-hal yang tidak penting. Mark Manson, melalui gaya bahasa yang blak-blakan dan humor sarkastik, menyampaikan bahwa kunci kebahagiaan bukanlah selalu positif atau bahagia, tapi menerima kenyataan hidup yang sulit dan memilah apa yang layak diperjuangkan.',
            'cover_url' => 'https://drive.google.com/thumbnail?id=1W4j-hRkaHwqzcFFjDLIgYXxIUoiIvXoC&sz=w1000',
        ]);
        $book1->genres()->attach([$sejarah->id, $thriller->id, $drama->id]);

        $book2 = Book::create([
            'title' => 'Perahu Kertas',
            'epub_path' => 'epubs/pg1342.epub',
            'author' => 'Dee Lestari',
            'publisher' => 'Bentang Pustaka',
            'year' => '2009',
            'isbn' => '9786028811638',
            'pages' => 444,
            'language' => 'Indonesia',
            'description' => 'Kisah cinta dua insan yang unik dan mengharukan.',
            'cover_url' => 'https://drive.google.com/thumbnail?id=1_RZxGYVxl7zGRoFKSxw5OGp-ETH_3X66&sz=w1000',
        ]);
        $book2->genres()->attach([$drama->id]);

        $book3 = Book::create([
            'title' => 'Sapiens: Riwayat Singkat Umat Manusia',
            'epub_path' => 'epubs/jesus-and-muhammad.epub',
            'author' => 'Yuval Noah Harari',
            'publisher' => 'KPG',
            'year' => '2017',
            'isbn' => '9786024242733',
            'pages' => 512,
            'language' => 'Indonesia',
            'description' => 'Buku ini membahas sejarah umat manusia dari zaman purba hingga era modern.',
        ]);
        $book3->genres()->attach([$sejarah->id]);

        $book4 = Book::create([
            'title' => 'Laskar Pelangi',
            'author' => 'Andrea Hirata',
            'publisher' => 'Bentang Pustaka',
            'year' => '2005',
            'isbn' => '9789793062799',
            'pages' => 529,
            'language' => 'Indonesia',
            'description' => 'Kisah inspiratif tentang perjuangan anak-anak Belitung untuk mendapatkan pendidikan.',
        ]);
        $book4->genres()->attach([$drama->id]);

        $book5 = Book::create([
            'title' => 'Atomic Habits',
            'author' => 'James Clear',
            'publisher' => 'Gramedia',
            'year' => '2018',
            'isbn' => '9786024526987',
            'pages' => 320,
            'language' => 'Indonesia',
            'description' => 'Panduan praktis untuk membangun kebiasaan baik dan menghentikan kebiasaan buruk.',
        ]);
        $book5->genres()->attach([$sejarah->id]);

        $book6 = Book::create([
            'title' => 'Bumi Manusia',
            'author' => 'Pramoedya Ananta Toer',
            'publisher' => 'Lentera Dipantara',
            'year' => '1980',
            'isbn' => '9789799731231',
            'pages' => 535,
            'language' => 'Indonesia',
            'description' => 'Kisah cinta dan perjuangan di masa kolonial Belanda.',
        ]);
        $book6->genres()->attach([$drama->id]);

        $book7 = Book::create([
            'title' => 'The Subtle Art of Not Giving a F*ck',
            'author' => 'Mark Manson',
            'publisher' => 'Gramedia',
            'year' => '2016',
            'isbn' => '9786024526988',
            'pages' => 224,
            'language' => 'Indonesia',
            'description' => 'Panduan untuk hidup lebih bermakna dengan fokus pada hal-hal penting.',
        ]);
        $book7->genres()->attach([$sejarah->id]);

        $book8 = Book::create([
            'title' => 'Supernova: Ksatria, Puteri, dan Bintang Jatuh',
            'author' => 'Dee Lestari',
            'publisher' => 'Bentang Pustaka',
            'year' => '2001',
            'isbn' => '9789793062798',
            'pages' => 246,
            'language' => 'Indonesia',
            'description' => 'Novel yang menggabungkan sains, filsafat, dan cinta.',
        ]);
        $book8->genres()->attach([$drama->id]);

        $book9 = Book::create([
            'title' => 'Think and Grow Rich',
            'author' => 'Napoleon Hill',
            'publisher' => 'Gramedia',
            'year' => '1937',
            'isbn' => '9786024526989',
            'pages' => 238,
            'language' => 'Indonesia',
            'description' => 'Panduan klasik untuk mencapai kesuksesan finansial dan pribadi.',
        ]);
        $book9->genres()->attach([$sejarah->id]);

        $book10 = Book::create([
            'title' => 'Ayat-Ayat Cinta',
            'author' => 'Habiburrahman El Shirazy',
            'publisher' => 'Republika',
            'year' => '2004',
            'isbn' => '9789791102076',
            'pages' => 418,
            'language' => 'Indonesia',
            'description' => 'Kisah cinta yang penuh dengan nilai-nilai Islam.',
        ]);
        $book10->genres()->attach([$drama->id]);

        $book11 = Book::create([
            'title' => 'Rich Dad Poor Dad',
            'author' => 'Robert T. Kiyosaki',
            'publisher' => 'Gramedia',
            'year' => '1997',
            'isbn' => '9786024526990',
            'pages' => 336,
            'language' => 'Indonesia',
            'description' => 'Buku tentang literasi keuangan dan cara mencapai kebebasan finansial.',
        ]);
        $book11->genres()->attach([$sejarah->id]);

        $book12 = Book::create([
            'title' => 'Dilan: Dia adalah Dilanku Tahun 1990',
            'author' => 'Pidi Baiq',
            'publisher' => 'Pastel Books',
            'year' => '2014',
            'isbn' => '9786027870410',
            'pages' => 332,
            'language' => 'Indonesia',
            'description' => 'Kisah cinta remaja yang manis dan menggemaskan.',
        ]);
        $book12->genres()->attach([$drama->id]);

        $book13 = Book::create([
            'title' => "Man's Search for Meaning",
            'author' => 'Viktor E. Frankl',
            'publisher' => 'Beacon Press',
            'year' => '1946',
            'isbn' => '9780807014295',
            'pages' => 200,
            'language' => 'Indonesia',
            'description' => 'Buku ini mengeksplorasi pencarian makna hidup berdasarkan pengalaman penulis di kamp konsentrasi Nazi.',
        ]);
        $book13->genres()->attach([$sejarah->id]);

        $book14 = Book::create([
            'title' => 'Harry Potter dan Batu Bertuah',
            'author' => 'J.K. Rowling',
            'publisher' => 'Gramedia',
            'year' => '1997',
            'isbn' => '9786024526991',
            'pages' => 320,
            'language' => 'Indonesia',
            'description' => 'Petualangan Harry Potter di dunia sihir yang penuh keajaiban.',
        ]);
        $book14->genres()->attach([$drama->id]);

        $book15 = Book::create([
            'title' => 'The Alchemist',
            'author' => 'Paulo Coelho',
            'publisher' => 'HarperOne',
            'year' => '1988',
            'isbn' => '9780061122415',
            'pages' => 208,
            'language' => 'Indonesia',
            'description' => 'Kisah inspiratif tentang perjalanan seorang gembala muda mencari harta karun.',
        ]);
        $book15->genres()->attach([$motivasi->id]);

        $book16 = Book::create([
            'title' => '1984',
            'author' => 'George Orwell',
            'publisher' => 'Gramedia',
            'year' => '1949',
            'isbn' => '9786024526992',
            'pages' => 328,
            'language' => 'Indonesia',
            'description' => 'Novel distopia tentang pengawasan totaliter dan kebebasan individu.',
        ]);
        $book16->genres()->attach([$thriller->id]);

        $book17 = Book::create([
            'title' => 'The Hobbit',
            'author' => 'J.R.R. Tolkien',
            'publisher' => 'Gramedia',
            'year' => '1937',
            'isbn' => '9786024526993',
            'pages' => 310,
            'language' => 'Indonesia',
            'description' => 'Petualangan Bilbo Baggins di dunia fantasi Middle-earth.',
        ]);
        $book17->genres()->attach([$fantasi->id]);

        $book18 = Book::create([
            'title' => 'To Kill a Mockingbird',
            'author' => 'Harper Lee',
            'publisher' => 'Gramedia',
            'year' => '1960',
            'isbn' => '9786024526994',
            'pages' => 281,
            'language' => 'Indonesia',
            'description' => 'Kisah tentang keadilan dan prasangka di Amerika Selatan.',
        ]);
        $book18->genres()->attach([$drama->id]);

        $book19 = Book::create([
            'title' => 'The Great Gatsby',
            'author' => 'F. Scott Fitzgerald',
            'publisher' => 'Gramedia',
            'year' => '1925',
            'isbn' => '9786024526995',
            'pages' => 180,
            'language' => 'Indonesia',
            'description' => 'Kisah tentang mimpi Amerika dan cinta yang tak terbalas.',
        ]);
        $book19->genres()->attach([$drama->id]);
    }
}
