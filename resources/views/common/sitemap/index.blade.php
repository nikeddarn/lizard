<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach( $siteMapItems as $item )
        <url>
            <loc>{{ $item->url }}</loc>
            <lastmod>{{ $item->lastmod }}</lastmod>
            <changefreq>{{ $item->changefreq }}</changefreq>
        </url>
    @endforeach
</urlset>
