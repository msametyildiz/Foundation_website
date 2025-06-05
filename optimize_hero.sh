#!/bin/bash

# Hero image optimization script
# Bu script hero görsellerini optimize eder ve WebP formatına çevirir

echo "🖼️  Hero görsel optimizasyonu başlatılıyor..."

cd /home/muhammed-samet-yildiz/necat_dernegi_site

# WebP desteği kontrolü
if ! command -v cwebp &> /dev/null; then
    echo "⚠️  WebP araçları bulunamadı. Yükleniyor..."
    sudo apt update && sudo apt install -y webp
fi

# Hero görselleri dizini
HERO_DIR="uploads/images/hero"

# Mevcut JPEG görseli optimize et
if [ -f "$HERO_DIR/hero-image.jpg" ]; then
    echo "📸 Hero JPEG optimize ediliyor..."
    
    # Orijinal yedekle
    cp "$HERO_DIR/hero-image.jpg" "$HERO_DIR/hero-image-original.jpg"
    
    # JPEG optimize et (kalite 85)
    if command -v jpegoptim &> /dev/null; then
        jpegoptim --max=85 --strip-all "$HERO_DIR/hero-image.jpg"
        echo "✅ JPEG optimizasyonu tamamlandı"
    fi
    
    # WebP versiyonu oluştur
    cwebp -q 85 "$HERO_DIR/hero-image.jpg" -o "$HERO_DIR/hero-image.webp"
    echo "✅ WebP versiyonu oluşturuldu"
    
    # Responsive boyutlar oluştur
    if command -v convert &> /dev/null; then
        echo "📱 Responsive boyutlar oluşturuluyor..."
        
        # Tablet boyutu (800px)
        convert "$HERO_DIR/hero-image.jpg" -resize 800x533 "$HERO_DIR/hero-image-tablet.jpg"
        cwebp -q 85 "$HERO_DIR/hero-image-tablet.jpg" -o "$HERO_DIR/hero-image-tablet.webp"
        
        # Mobile boyutu (600px)
        convert "$HERO_DIR/hero-image.jpg" -resize 600x400 "$HERO_DIR/hero-image-mobile.jpg"
        cwebp -q 85 "$HERO_DIR/hero-image-mobile.jpg" -o "$HERO_DIR/hero-image-mobile.webp"
        
        echo "✅ Responsive boyutlar oluşturuldu"
    fi
    
    echo "🎉 Hero görsel optimizasyonu tamamlandı!"
    echo "📊 Dosya boyutları:"
    ls -lh "$HERO_DIR"/hero-image*
else
    echo "❌ Hero görseli bulunamadı: $HERO_DIR/hero-image.jpg"
fi
