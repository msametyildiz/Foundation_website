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
    private $errorHandlers = [
        '404' => 'pages/404.php',
        '403' => 'pages/403.php',
        '500' => 'pages/500.php',
    ];
    
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
     * Toplu rota ekleme
     * 
     * @param array $routes Rotaları içeren dizi
     * @return Router Zincirleme çağrılar için
     */
    public function addRoutes($routes) {
        foreach ($routes as $path => $handler) {
            $this->add($path, $handler);
        }
        return $this;
    }
    
    /**
     * Özel hata sayfası ayarlama
     *
     * @param string $code Hata kodu (404, 403, 500)
     * @param string $handler Hata sayfası dosyası
     * @return Router Zincirleme çağrılar için
     */
    public function setErrorHandler($code, $handler) {
        $this->errorHandlers[$code] = $handler;
        if ($code === '404') {
            $this->notFoundHandler = $handler;
        }
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
        $this->errorHandlers['404'] = $handler;
        return $this;
    }
    
    /**
     * İstek URI'sinden yolu çıkarır
     * 
     * @return string Temizlenmiş yol
     */
    public function getCurrentPath() {
        // URL yolunu al
        $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        
        // index.php'yi kaldır
        $path = str_replace('index.php', '', $path);
        $path = trim($path, '/');
        
        return $path;
    }
    
    /**
     * İstenilen yola göre doğru sayfayı belirler
     *
     * @param string $path İstenilen URL yolu (boş bırakılırsa mevcut URI kullanılır)
     * @return string İşlenecek PHP dosyası
     */
    public function dispatch($path = null) {
        // Path belirtilmemişse mevcut URI'den al
        if ($path === null) {
            $path = $this->getCurrentPath();
        }
        
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
    
    /**
     * Hata sayfasını döndür
     * 
     * @param string $code Hata kodu (404, 403, 500)
     * @return string Hata sayfası dosyası
     */
    public function getErrorPage($code) {
        return isset($this->errorHandlers[$code]) ? $this->errorHandlers[$code] : $this->notFoundHandler;
    }
} 