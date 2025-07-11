<?php
/**
 * Router Sınıfı
 * SEO dostu URL yönlendirme sistemi
 * 
 * Örnek kullanım:
 * $router = new Router();
 * $router->add('projeler', 'pages/projects.php');
 * $page_file = $router->dispatch('projeler');
 */
class Router {
    private $routes = [];
    private $notFoundHandler = 'pages/404.php';
    
    /**
     * Yeni bir rota ekler
     *
     * @param string $path URL yolu (örn: 'iletisim')
     * @param string $handler İşlenecek dosya (örn: 'pages/contact.php')
     * @return Router Zincirleme çağrılar için
     */
    public function add($path, $handler) {
        $this->routes[$path] = $handler;
        return $this;
    }
    
    /**
     * 404 hata sayfasını ayarlar
     *
     * @param string $handler 404 sayfası
     * @return Router Zincirleme çağrılar için
     */
    public function setNotFound($handler) {
        $this->notFoundHandler = $handler;
        return $this;
    }
    
    /**
     * İstenilen yola göre doğru sayfayı belirler
     *
     * @param string $path İstenilen URL yolu
     * @return string İşlenecek PHP dosyası
     */
    public function dispatch($path) {
        // Ana sayfa kontrolü
        if ($path === '' || $path === '/' || $path === 'home') {
            return isset($this->routes['home']) ? $this->routes['home'] : 'pages/home.php';
        }
        
        // Normal sayfa kontrolü
        if (isset($this->routes[$path])) {
            return $this->routes[$path];
        }
        
        // ID içeren sayfalar için kontrol (örn: projeler/5)
        if (strpos($path, '/') !== false) {
            list($basePath, $id) = explode('/', $path, 2);
            if (isset($this->routes[$basePath])) {
                $_GET['id'] = $id; // ID'yi GET parametresi olarak ayarla
                return $this->routes[$basePath];
            }
        }
        
        // Bulunamadı, 404 sayfası döndür
        return $this->notFoundHandler;
    }
} 