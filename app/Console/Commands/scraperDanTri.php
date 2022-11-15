<?php

namespace App\Console\Commands;

use Str;
use Goutte;
use Illuminate\Console\Command;
use App\Models\Post;

class scraperDanTri extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'command:name';
    protected $signature = 'scrape:dantri';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    
    protected $category = [
        'su-kien.htm',
        'xa-hoi.htm',
        'the-gioi.htm',
        'kinh-doanh.htm',
        'bat-dong-san.htm',
        'the-thao.htm',
        'viec-lam.htm',
        'nhan-ai.htm',
        'suc-khoe.htm',
        'van-hoa.htm',
        'giai-tri.htm',
        'o-to-xe-may.htm',
        'suc-manh-so.htm',
        'giao-duc-huong-nghiep.htm',
        'an-sinh.htm',
        'phap-luat.htm',
        'o-to-xe-may.htm'
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(){
        foreach($this->category as $category){
            print("Lấy danh mục " .  $category . "\n");
            $l = env("DANTRI") ."/".$category;
            $crawler = Goutte::request('GET', $category);
            $linkPost = $crawler->filter('h3.article-title a')->each( function ($node)
            {
                return $node->attr('href');
            });

            foreach ($linkPost as $link) {
                $l = env("DANTRI").$link;
                self::scrapePost($l);
            }
        }
        $crawler = Goutte::request('GET', 'https://dantri.com.vn/kinh-doanh/10-thoi-quen-nen-hoc-cho-nhung-ai-muon-tro-nen-giau-co-20220110075422977.htm');
        $tieude = $crawler->filter('h1.title-page.detail')->each(function ($node) {
            return $node->text();
        })[0];
        $slug = Str::slug($tieude);

        $mota = $crawler->filter('h2.singular-sapo')->each(function ($node) {
            return $node->text();
        })[0];
        $mota = str_replace('(Dân trí) - ', '', $mota);
        $noidung = $crawler->filter('.singular-content')->each(function ($node) {
            return $node->text();
        })[0];
        $thoigian = $crawler->filter('.author-time')->each(function ($node) {
            return $node->text();
        })[0];
        $tacgia = $crawler->filter('.author-name')->each(function ($node) {
            // print( $node->text() ."\n");
            return $node->text();
        })[0];
        $hinh = $crawler->filter('figure.image.align-center img')->each(function ($node) {
            return $node->attr('src');
        })[0];
        $data = [
            'tieude' => $tieude,
            'noidung' => $noidung,
            'slug' => $slug,
            'ngay' => $thoigian,
            'mota' => $mota,
            'tacgia' => $tacgia,
            'hinh' => $hinh
        ];
        Post::create($data);
        print('Lấy thành công dữ liệu');
    }

    // public static function crawlPost($url){
    //     $crawler = Goutte::request('GET', $url);
    //     $tieude = $crawler->filter('h1.title-page.detail')->each(function ($node) {
    //         return $node->text();
    //     })[0];
    //     $slug = Str::slug($tieude);
    //     $mota = $crawler->filter('h2.singular-sapo')->each(function ($node) {
    //         return $node->text();
    //     })[0];
    //     // $mota = str_replace('Dân trí', '' , $mota[0]);
    //     $noidung = $crawler->filter('.singular-content')->each(function ($node) {
    //         return $node->text();
    //     })[0];
    //     $thoigian = $crawler->filter('.author-time')->each(function ($node) {
    //         return $node->text();
    //     })[0];
    //     $tacgia = $crawler->filter('.author-name')->each(function ($node) {
    //         // print( $node->text() ."\n");
    //         return $node->text();
    //     })[0];
    //     $hinh = $crawler->filter('figure.image.align-center img')->each(function ($node) {
    //         return $node->attr('src');
    //     })[0];
    //     $data = [
    //         'tieude' => $tieude,
    //         'noidung' => $noidung,
    //         'slug' => $slug,
    //         'ngay' => $thoigian,
    //         'mota' => $mota,
    //         'tacgia' => $tacgia,
    //         'hinh' => $hinh
    //     ];
    //     Post::create($data);

    //     print('Lấy thành công dữ liệu');
    // }
}
