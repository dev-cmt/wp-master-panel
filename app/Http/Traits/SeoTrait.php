<?php

namespace App\Http\Traits;

trait SeoTrait
{
    protected array $seo = [];

    public function setSeo(array $data): self
    {
        $this->seo = array_merge([
            'title'       => config('app.name'),
            'description' => '',
            'keywords'    => '',
            'image'       => '',
            'canonical'   => null,
            'robots'      => 'index,follow',
        ], $data);

        return $this;
    }
    /**-----------------------------------------------------------------------
     * Generate Tags Meta, Canonical, OG, Twitter
     * -----------------------------------------------------------------------
     */
    public function generateTags(): string
    {
        $seo = $this->seo;
        $og = config('seo.og', [
            'type' => 'website',
            'site_name' => config('app.name'),
            'locale' => app()->getLocale() . '_' . strtoupper(app()->getLocale())
        ]);

        $tags = [
            // Basic Meta
            '<title>' . e($seo['title']) . '</title>',
            '<meta name="description" content="' . e($seo['description']) . '">',
            '<meta name="keywords" content="' . e($seo['keywords']) . '">',
            '<meta name="robots" content="' . e($seo['robots']) . '">',

            // Open Graph
            '<meta property="og:title" content="' . e($seo['title']) . '">',
            '<meta property="og:description" content="' . e($seo['description']) . '">',
            '<meta property="og:type" content="' . e($og['type']) . '">',
            '<meta property="og:url" content="' . e(url()->current()) . '">',
            '<meta property="og:site_name" content="' . e($og['site_name']) . '">',
            '<meta property="og:locale" content="' . e($og['locale']) . '">',

            // Twitter Card
            '<meta name="twitter:card" content="summary_large_image">',
            '<meta name="twitter:title" content="' . e($seo['title']) . '">',
            '<meta name="twitter:description" content="' . e($seo['description']) . '">'
        ];
        // Facebook
        if ($fbAppId = env('FACEBOOK_APP_ID')) {
            $tags[] = '<meta property="fb:app_id" content="' . e($fbAppId) . '">';
        }

        // Canonical URL
        if (!empty($seo['canonical'])) {
            $tags[] = '<link rel="canonical" href="' . e($seo['canonical']) . '">';
        }

        // Image meta tags
        if (!empty($seo['image'])) {
            $imageUrl = asset($seo['image']);
            $imageTags = [
                '<meta property="og:image" content="' . e($imageUrl) . '">',
                '<meta name="twitter:image" content="' . e($imageUrl) . '">',
                '<meta name="pinterest-rich-pin" content="true">',
                '<meta property="og:image:width" content="1200">',
                '<meta property="og:image:height" content="630">'
            ];
            $tags = array_merge($tags, $imageTags);
        }

        return implode("\n", $tags);
    }

    /**-----------------------------------------------------------------------
     * Generate Breadcrumbs JSON-LD
     * -----------------------------------------------------------------------
     */
    public function generateBreadcrumbJsonLd(array $items): string
    {
        $breadcrumbs = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [],
        ];

        foreach ($items as $position => $item) {
            $breadcrumbs['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $position + 1,
                'name' => $item['name'],
                'item' => $item['url'] ?? null,
            ];
        }

        return '<script type="application/ld+json">' . json_encode($breadcrumbs) . '</script>';
    }

    /**-----------------------------------------------------------------------
     * Product JSON-LD
     * -----------------------------------------------------------------------
     */
    public function generateProductJsonLd($product): string
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => $product->meta_description,
            // 'brand' => [
            //     '@type' => 'Brand',
            //     'name' => $product->brand->name,
            // ],
            'offers' => [
                '@type' => 'Offer',
                'url' => route('single.product', [$product->slug, $product->id]),
                'priceCurrency' => 'BDT',
                'price' => $product->price,
                'availability' => $product->stock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock'
            ],
        ];

        // if ($product->reviews->count() > 0) {
        //     $data['aggregateRating'] = [
        //         '@type' => 'AggregateRating',
        //         'ratingValue' => $product->reviews->avg('rating'),
        //         'reviewCount' => $product->reviews->count(),
        //     ];
        // }

        return '<script type="application/ld+json">' . json_encode($data) . '</script>';
    }

    /**-----------------------------------------------------------------------
     * Article JSON-LD
     * -----------------------------------------------------------------------
     */
    public function generateArticleJsonLd($article): string
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $article->title,
            'description' => $article->excerpt,
            'author' => [
                '@type' => 'Person',
                'name' => $article->author->name,
            ],
            'datePublished' => $article->published_at->toIso8601String(),
            'dateModified' => $article->updated_at->toIso8601String(),
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('app.name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('images/logo.png'),
                ],
            ],
        ];

        if ($article->featured_image) {
            $data['image'] = asset($article->featured_image);
        }

        return '<script type="application/ld+json">' . json_encode($data) . '</script>';
    }

    /**-----------------------------------------------------------------------
     * Article JSON-LD
     * -----------------------------------------------------------------------
     */
    protected function formatKeywords(?string $keywords): string
    {
        // If empty, return default
        if (empty($keywords)) {
            return 'default,keywords';
        }

        // Convert to lowercase and clean
        $keywords = strtolower($keywords);

        // Replace any whitespace around commas with single comma
        $keywords = preg_replace('/\s*,\s*/', ',', $keywords);

        // Replace multiple commas with single comma
        $keywords = preg_replace('/,+/', ',', $keywords);

        // Trim leading/trailing commas and whitespace
        $keywords = trim($keywords, " ,");

        // Replace remaining spaces with commas (for "keywords sagour" case)
        $keywords = preg_replace('/\s+/', ',', $keywords);

        return $keywords ?: 'default,keywords';
    }


}
