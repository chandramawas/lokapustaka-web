<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
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
        $nonFiction = Category::where('name', 'Non-Fiksi')->first();
        $novel = Category::where('name', 'Novel')->first();

        $drama = Genre::where('name', 'Drama')->first();
        $thriller = Genre::where('name', 'Thriller')->first();
        $sejarah = Genre::where('name', 'Sejarah')->first();

        $book1 = Book::create([
            'category_id' => $nonFiction->id,
            'title' => 'Sejarah Dunia yang Disembunyikan',
            'author' => 'Jonathan Black',
            'publisher' => 'Gramedia',
            'year' => '2013',
            'isbn_issn' => '9786020328615',
            'pages' => 480,
            'language' => 'Indonesia',
            'description' => 'Buku ini adalah panduan anti-mainstream tentang bagaimana menjalani hidup yang lebih bermakna dengan tidak peduli terhadap hal-hal yang tidak penting. Mark Manson, melalui gaya bahasa yang blak-blakan dan humor sarkastik, menyampaikan bahwa kunci kebahagiaan bukanlah selalu positif atau bahagia, tapi menerima kenyataan hidup yang sulit dan memilah apa yang layak diperjuangkan.',
            'cover_url' => 'https://drive.google.com/thumbnail?id=1W4j-hRkaHwqzcFFjDLIgYXxIUoiIvXoC&sz=w1000',
        ]);
        $book1->genres()->attach([$sejarah->id, $thriller->id, $drama->id]);

        $book2 = Book::create([
            'category_id' => $novel->id,
            'title' => 'Perahu Kertas',
            'author' => 'Dee Lestari',
            'publisher' => 'Bentang Pustaka',
            'year' => '2009',
            'isbn_issn' => '9786028811638',
            'pages' => 444,
            'language' => 'Indonesia',
            'description' => 'Kisah cinta dua insan yang unik dan mengharukan.',
            'cover_url' => 'https://drive.google.com/thumbnail?id=1_RZxGYVxl7zGRoFKSxw5OGp-ETH_3X66&sz=w1000',
        ]);
        $book2->genres()->attach([$drama->id]);
    }
}
