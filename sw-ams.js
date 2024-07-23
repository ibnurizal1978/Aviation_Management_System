self.addEventListener('install', function(e) {
 e.waitUntil(
   caches.open('airhorner').then(function(cache) {
     return cache.addAll([
      '',
      'assets/css/themes/earth.min.css',
      'assets/css/codebase.css',
	  'assets/css/codebase.min.css',
	  'assets/fonts/fontawesome-webfont.woff2',
	  'assets/fonts/Simple-Line-Icons.woff2',
	  'assets/js/codebase.js',
	  'assets/js/core/bootstrap.bundle.min.js',
	  'assets/js/core/jquery.min.js',
	  'assets/js/core/jquery.scrollLock.min.js',
	  'assets/js/core/jquery.slimscroll.min.js',
	  'x=v7dj45698=!as7k/aircraft-certificate.php'
     ]);
   })
 );
});