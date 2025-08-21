<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContentManagementController extends Controller
{
    /**
     * Display the content management dashboard
     */
    public function index()
    {
        $stats = [
            'features_count' => 6, // Default features
            'testimonials_count' => $this->getTestimonialsCount(),
            'help_articles_count' => $this->getHelpArticlesCount(),
            'contact_messages_count' => $this->getContactMessagesCount(),
        ];
        
        return view('admin.content.unified', compact('stats'));
    }

    /**
     * Landing Page Features Management
     */
    public function features()
    {
        $features = $this->getFeatures();
        return view('admin.content.features', compact('features'));
    }

    /**
     * Store or update features
     */
    public function storeFeatures(Request $request)
    {
        $request->validate([
            'features' => 'required|array|min:1',
            'features.*.title' => 'required|string|max:255',
            'features.*.description' => 'required|string',
            'features.*.icon' => 'required|string',
            'features.*.color' => 'required|string',
        ]);

        $this->saveFeatures($request->features);

        return redirect()->route('admin.content.features')
            ->with('success', 'Fitur berhasil diperbarui!');
    }

    /**
     * Testimonials Management
     */
    public function testimonials()
    {
        $testimonials = $this->getTestimonials();
        return view('admin.content.testimonials', compact('testimonials'));
    }

    /**
     * Store testimonial
     */
    public function storeTestimonial(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'message' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'avatar' => 'nullable|url',
            'is_featured' => 'boolean',
        ]);

        $testimonials = $this->getTestimonials();
        
        $newTestimonial = [
            'name' => $request->name,
            'position' => $request->position ?? 'Customer',
            'email' => $request->email,
            'message' => $request->message,
            'rating' => (int) $request->rating,
            'avatar' => $request->avatar,
            'is_featured' => $request->boolean('is_featured'),
            'created_at' => now()->toISOString(),
        ];

        $testimonials[] = $newTestimonial;
        $this->saveTestimonials($testimonials);

        return response()->json(['success' => true, 'message' => 'Testimonial berhasil ditambahkan']);
    }

    /**
     * Delete testimonial
     */
    public function deleteTestimonial($id)
    {
        $testimonials = $this->getTestimonials();
        
        if ($id < count($testimonials)) {
            unset($testimonials[$id]);
            $testimonials = array_values($testimonials);
            $this->saveTestimonials($testimonials);
            
            return response()->json(['success' => true, 'message' => 'Testimonial berhasil dihapus']);
        }
        
        return response()->json(['success' => false, 'message' => 'Testimonial tidak ditemukan']);
    }

    /**
     * Update testimonial
     */
    public function updateTestimonial(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'message' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'avatar' => 'nullable|url',
            'is_featured' => 'boolean',
        ]);

        $testimonials = $this->getTestimonials();
        
        if ($id < count($testimonials)) {
            $testimonials[$id]['name'] = $request->name;
            $testimonials[$id]['position'] = $request->position;
            $testimonials[$id]['email'] = $request->email;
            $testimonials[$id]['message'] = $request->message;
            $testimonials[$id]['rating'] = $request->rating;
            $testimonials[$id]['avatar'] = $request->avatar;
            $testimonials[$id]['is_featured'] = $request->boolean('is_featured');
            $testimonials[$id]['updated_at'] = now()->toISOString();
            
            $this->saveTestimonials($testimonials);
            
            return response()->json(['success' => true, 'message' => 'Testimonial berhasil diupdate']);
        }
        
        return response()->json(['success' => false, 'message' => 'Testimonial tidak ditemukan']);
    }

    /**
     * Toggle testimonial featured status
     */
    public function toggleTestimonialFeatured(Request $request, $id)
    {
        $testimonials = $this->getTestimonials();
        
        if ($id < count($testimonials)) {
            $testimonials[$id]['is_featured'] = $request->boolean('is_featured');
            $testimonials[$id]['updated_at'] = now()->toISOString();
            
            $this->saveTestimonials($testimonials);
            
            return response()->json(['success' => true, 'message' => 'Status featured berhasil diupdate']);
        }
        
        return response()->json(['success' => false, 'message' => 'Testimonial tidak ditemukan']);
    }

    /**
     * Help Center Management
     */
    public function helpCenter()
    {
        $helpArticles = $this->getHelpArticles();
        return view('admin.content.help-center', compact('helpArticles'));
    }

    /**
     * Store help article
     */
    public function storeHelpArticle(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'content' => 'required|string',
            'tags' => 'nullable|string',
            'is_featured' => 'boolean',
        ]);

        $articles = $this->getHelpArticles();
        
        $tags = $request->tags ? array_map('trim', explode(',', $request->tags)) : [];
        
        $newArticle = [
            'title' => $request->title,
            'category' => $request->category,
            'content' => $request->content,
            'tags' => $tags,
            'is_featured' => $request->boolean('is_featured'),
            'views' => 0,
            'created_at' => now()->toISOString(),
        ];

        $articles[] = $newArticle;
        $this->saveHelpArticles($articles);

        return response()->json(['success' => true, 'message' => 'Artikel bantuan berhasil ditambahkan']);
    }

    /**
     * Update help article
     */
    public function updateHelpArticle(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'content' => 'required|string',
            'tags' => 'nullable|string',
            'is_featured' => 'boolean',
        ]);

        $articles = $this->getHelpArticles();
        
        if ($id < count($articles)) {
            $tags = $request->tags ? array_map('trim', explode(',', $request->tags)) : [];
            
            $articles[$id]['title'] = $request->title;
            $articles[$id]['category'] = $request->category;
            $articles[$id]['content'] = $request->content;
            $articles[$id]['tags'] = $tags;
            $articles[$id]['is_featured'] = $request->boolean('is_featured');
            $articles[$id]['updated_at'] = now()->toISOString();
            
            $this->saveHelpArticles($articles);
            
            return response()->json(['success' => true, 'message' => 'Artikel berhasil diupdate']);
        }
        
        return response()->json(['success' => false, 'message' => 'Artikel tidak ditemukan']);
    }

    /**
     * Toggle help article featured status
     */
    public function toggleHelpArticleFeatured(Request $request, $id)
    {
        $articles = $this->getHelpArticles();
        
        if ($id < count($articles)) {
            $articles[$id]['is_featured'] = $request->boolean('is_featured');
            $articles[$id]['updated_at'] = now()->toISOString();
            
            $this->saveHelpArticles($articles);
            
            return response()->json(['success' => true, 'message' => 'Status featured berhasil diupdate']);
        }
        
        return response()->json(['success' => false, 'message' => 'Artikel tidak ditemukan']);
    }

    /**
     * Delete help article
     */
    public function deleteHelpArticle($id)
    {
        $articles = $this->getHelpArticles();
        
        if ($id < count($articles)) {
            unset($articles[$id]);
            $articles = array_values($articles);
            $this->saveHelpArticles($articles);
            
            return response()->json(['success' => true, 'message' => 'Artikel berhasil dihapus']);
        }
        
        return response()->json(['success' => false, 'message' => 'Artikel tidak ditemukan']);
    }

    /**
     * Contact Messages Management
     */
    public function contactMessages()
    {
        $messages = $this->getContactMessages();
        return view('admin.content.contact-messages', compact('messages'));
    }

    /**
     * Store contact message (from public form)
     */
    public function storeContactMessage(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $messages = $this->getContactMessages();
        
        $newMessage = [
            'id' => count($messages) + 1,
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'unread',
            'created_at' => now()->toISOString(),
        ];

        $messages[] = $newMessage;
        $this->saveContactMessages($messages);

        return response()->json([
            'success' => true,
            'message' => 'Pesan Anda berhasil dikirim! Kami akan merespons dalam 24 jam.'
        ]);
    }

    /**
     * Mark message as read
     */
    public function markMessageAsRead(Request $request, $id)
    {
        $messages = $this->getContactMessages();
        
        if ($id < count($messages)) {
            $messages[$id]['is_read'] = $request->boolean('is_read');
            $messages[$id]['read_at'] = $request->boolean('is_read') ? now()->toISOString() : null;
            
            $this->saveContactMessages($messages);
            
            return response()->json(['success' => true, 'message' => 'Status pesan berhasil diupdate']);
        }
        
        return response()->json(['success' => false, 'message' => 'Pesan tidak ditemukan']);
    }

    /**
     * Delete contact message
     */
    public function deleteContactMessage($id)
    {
        $messages = $this->getContactMessages();
        
        if ($id < count($messages)) {
            unset($messages[$id]);
            $messages = array_values($messages);
            $this->saveContactMessages($messages);
            
            return response()->json(['success' => true, 'message' => 'Pesan berhasil dihapus']);
        }
        
        return response()->json(['success' => false, 'message' => 'Pesan tidak ditemukan']);
    }

    /**
     * Mark all messages as read
     */
    public function markAllMessagesAsRead()
    {
        $messages = $this->getContactMessages();
        
        foreach ($messages as &$message) {
            $message['is_read'] = true;
            $message['read_at'] = now()->toISOString();
        }
        
        $this->saveContactMessages($messages);
        
        return response()->json(['success' => true, 'message' => 'Semua pesan ditandai sebagai dibaca']);
    }

    /**
     * Delete all read messages
     */
    public function deleteReadMessages()
    {
        $messages = $this->getContactMessages();
        $messages = array_filter($messages, fn($msg) => !($msg['is_read'] ?? false));
        $messages = array_values($messages);
        
        $this->saveContactMessages($messages);
        
        return response()->json(['success' => true, 'message' => 'Semua pesan yang sudah dibaca berhasil dihapus']);
    }

    /**
     * Website Settings Management
     */
    public function websiteSettings()
    {
        $settings = $this->getWebsiteSettings();
        return view('admin.content.website-settings', compact('settings'));
    }

    /**
     * Store website settings
     */
    public function storeWebsiteSettings(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'required|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string',
            'contact_address' => 'required|string',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'hero_title' => 'required|string',
            'hero_subtitle' => 'required|string',
            'statistics' => 'required|array',
        ]);

        $this->saveWebsiteSettings($request->all());

        return redirect()->route('admin.content.website-settings')
            ->with('success', 'Pengaturan website berhasil diperbarui!');
    }

    // Helper methods for data management
    private function getFeatures()
    {
        $default = [
            ['title' => 'Hemat Waktu', 'description' => 'Tidak perlu mengantri lama di kantin. Pesan online dan langsung ambil ketika siap.', 'icon' => 'fas fa-clock', 'color' => 'orange'],
            ['title' => 'Mudah Digunakan', 'description' => 'Interface yang sederhana dan intuitif memudahkan siapa saja untuk memesan makanan.', 'icon' => 'fas fa-mobile-alt', 'color' => 'blue'],
            ['title' => 'Harga Terjangkau', 'description' => 'Berbagai pilihan makanan dengan harga yang ramah kantong mahasiswa.', 'icon' => 'fas fa-money-bill-wave', 'color' => 'green'],
            ['title' => 'Beragam Menu', 'description' => 'Puluhan warung dengan ratusan pilihan menu dari berbagai daerah.', 'icon' => 'fas fa-utensils', 'color' => 'purple'],
            ['title' => 'Aman & Terpercaya', 'description' => 'Sistem pembayaran yang aman dan penjual terverifikasi untuk kenyamanan Anda.', 'icon' => 'fas fa-shield-alt', 'color' => 'red'],
            ['title' => 'Support 24/7', 'description' => 'Tim dukungan siap membantu Anda kapan saja jika mengalami kendala.', 'icon' => 'fas fa-headset', 'color' => 'yellow'],
        ];

        return json_decode(Storage::disk('local')->get('admin/features.json') ?? json_encode($default), true);
    }

    private function saveFeatures($features)
    {
        Storage::disk('local')->put('admin/features.json', json_encode($features, JSON_PRETTY_PRINT));
    }

    private function getTestimonials()
    {
        $default = [
            [
                'name' => 'Sarah Mahasiswa',
                'position' => 'Mahasiswa Teknik Informatika',
                'email' => 'sarah@student.com',
                'rating' => 5,
                'message' => 'NganTeen sangat memudahkan hidup saya sebagai mahasiswa. Tidak perlu antri lagi di kantin, tinggal pesan lewat aplikasi dan makanan langsung siap diambil. Sangat praktis!',
                'avatar' => 'https://images.unsplash.com/photo-1494790108755-2616b612c962?w=150&h=150&fit=crop&crop=face',
                'is_featured' => true,
                'created_at' => now()->subDays(10)->toISOString(),
            ],
            [
                'name' => 'Ahmad Rizki',
                'position' => 'Mahasiswa Ekonomi',
                'email' => 'ahmad@student.com',
                'rating' => 5,
                'message' => 'Aplikasi yang sangat membantu! Saya bisa pesan makanan dari berbagai warung di kampus tanpa harus keliling. Fitur pre-order juga sangat berguna untuk menghemat waktu.',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop&crop=face',
                'is_featured' => true,
                'created_at' => now()->subDays(8)->toISOString(),
            ],
            [
                'name' => 'Dian Pertiwi',
                'position' => 'Mahasiswa Psikologi',
                'email' => 'dian@student.com',
                'rating' => 4,
                'message' => 'Interface yang user-friendly dan mudah digunakan. Sistem pembayaran juga aman. Sangat merekomendasikan untuk teman-teman mahasiswa lainnya.',
                'avatar' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=150&h=150&fit=crop&crop=face',
                'is_featured' => false,
                'created_at' => now()->subDays(5)->toISOString(),
            ],
            [
                'name' => 'Budi Santoso',
                'position' => 'Mahasiswa Kedokteran',
                'email' => 'budi@student.com',
                'rating' => 5,
                'message' => 'Sebagai mahasiswa kedokteran yang jadwalnya padat, NganTeen benar-benar menyelamatkan! Bisa pesan makanan di sela-sela kuliah dan langsung ambil. Efisien sekali!',
                'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop&crop=face',
                'is_featured' => true,
                'created_at' => now()->subDays(3)->toISOString(),
            ],
            [
                'name' => 'Lisa Anggraini',
                'position' => 'Mahasiswa Desain',
                'email' => 'lisa@student.com',
                'rating' => 4,
                'message' => 'Desain aplikasinya bagus dan modern. Pilihan makanannya juga banyak dari berbagai warung kampus. Pengalaman ordering yang menyenangkan!',
                'avatar' => 'https://images.unsplash.com/photo-1489424731084-a5d8b219a5bb?w=150&h=150&fit=crop&crop=face',
                'is_featured' => false,
                'created_at' => now()->subDays(1)->toISOString(),
            ],
        ];

        return json_decode(Storage::disk('local')->get('admin/testimonials.json') ?? json_encode($default), true);
    }

    private function saveTestimonials($testimonials)
    {
        Storage::disk('local')->put('admin/testimonials.json', json_encode($testimonials, JSON_PRETTY_PRINT));
    }

    private function getTestimonialsCount()
    {
        return count($this->getTestimonials());
    }

    private function getHelpArticles()
    {
        $default = [
            [
                'id' => 1,
                'title' => 'Cara Mendaftar sebagai Pembeli',
                'category' => 'Pembeli',
                'content' => 'Panduan lengkap untuk mendaftar sebagai pembeli di NganTeen...',
                'is_featured' => true,
                'created_at' => now()->toISOString(),
            ],
            [
                'id' => 2,
                'title' => 'Cara Mendaftar sebagai Penjual',
                'category' => 'Penjual',
                'content' => 'Panduan lengkap untuk mendaftar sebagai penjual di NganTeen...',
                'is_featured' => true,
                'created_at' => now()->toISOString(),
            ],
        ];

        return json_decode(Storage::disk('local')->get('admin/help-articles.json') ?? json_encode($default), true);
    }

    private function saveHelpArticles($articles)
    {
        Storage::disk('local')->put('admin/help-articles.json', json_encode($articles, JSON_PRETTY_PRINT));
    }

    private function getHelpArticlesCount()
    {
        return count($this->getHelpArticles());
    }

    private function getContactMessages()
    {
        $default = [
            [
                'name' => 'Sarah Mahasiswa',
                'email' => 'sarah@student.ac.id',
                'subject' => 'Pertanyaan tentang Cara Pemesanan',
                'phone' => '+62 812-3456-7890',
                'message' => 'Halo, saya ingin bertanya tentang cara memesan makanan melalui platform NganTeen. Apakah ada panduan step-by-step yang bisa saya ikuti? Terima kasih.',
                'is_read' => false,
                'created_at' => now()->subDays(2)->toISOString(),
            ],
            [
                'name' => 'Ahmad Rizki',
                'email' => 'ahmad.rizki@gmail.com',
                'subject' => 'Masalah Pembayaran',
                'phone' => '+62 813-7890-1234',
                'message' => 'Saya mengalami kesulitan dalam melakukan pembayaran. Setelah memilih metode pembayaran, aplikasi tidak merespons. Mohon bantuannya.',
                'is_read' => true,
                'read_at' => now()->subDays(1)->toISOString(),
                'created_at' => now()->subDays(3)->toISOString(),
            ],
            [
                'name' => 'Dian Pertiwi',
                'email' => 'dian.pertiwi@yahoo.com',
                'subject' => 'Saran Fitur Baru',
                'message' => 'Saya sangat suka dengan platform NganTeen! Saya ingin menyarankan penambahan fitur rating dan review untuk setiap warung. Ini akan membantu mahasiswa lain dalam memilih makanan.',
                'is_read' => false,
                'created_at' => now()->subDays(1)->toISOString(),
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@student.ac.id',
                'subject' => 'Kendala Teknis',
                'phone' => '+62 814-5678-9012',
                'message' => 'Aplikasi sering crash ketika saya sedang browsing menu. Saya menggunakan Android versi 11. Apakah ada update terbaru untuk memperbaiki bug ini?',
                'is_read' => true,
                'read_at' => now()->subHours(6)->toISOString(),
                'created_at' => now()->subHours(8)->toISOString(),
            ],
            [
                'name' => 'Lisa Anggraini',
                'email' => 'lisa.anggraini@outlook.com',
                'subject' => 'Kerjasama Warung',
                'message' => 'Saya pemilik warung di area kampus dan tertarik untuk bergabung dengan platform NganTeen. Bagaimana prosedur dan persyaratan untuk menjadi mitra? Mohon informasinya.',
                'is_read' => false,
                'created_at' => now()->subHours(4)->toISOString(),
            ],
        ];

        return json_decode(Storage::disk('local')->get('admin/contact-messages.json') ?? json_encode($default), true);
    }

    private function saveContactMessages($messages)
    {
        Storage::disk('local')->put('admin/contact-messages.json', json_encode($messages, JSON_PRETTY_PRINT));
    }

    private function getContactMessagesCount()
    {
        return count($this->getContactMessages());
    }

    private function getWebsiteSettings()
    {
        $default = [
            'site_name' => 'NganTeen',
            'site_description' => 'Platform kuliner kampus yang memudahkan mahasiswa memesan makanan tanpa antri',
            'contact_email' => 'support@nganteen.com',
            'contact_phone' => '+62 812-3456-7890',
            'contact_address' => 'Kampus University',
            'facebook_url' => '#',
            'instagram_url' => '#',
            'twitter_url' => '#',
            'hero_title' => 'Pesan Makanan Tanpa Antri',
            'hero_subtitle' => 'Solusi cerdas untuk mahasiswa yang ingin menikmati makanan lezat tanpa repot mengantri di kantin',
            'statistics' => [
                'menus' => 500,
                'warungs' => 50,
                'users' => 1000,
            ],
        ];

        return json_decode(Storage::disk('local')->get('admin/website-settings.json') ?? json_encode($default), true);
    }

    private function saveWebsiteSettings($settings)
    {
        Storage::disk('local')->put('admin/website-settings.json', json_encode($settings, JSON_PRETTY_PRINT));
    }
}
