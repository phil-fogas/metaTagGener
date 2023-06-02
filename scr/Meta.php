<?php

declare(strict_types=1);

//** version 1.5 de metaTagGener */

class Meta
{
  private array $android = [];
  private array $ios = [];
  private array $windows = [];
  private array $mini = [];
  private array $base = [];


  private array $option = [];
  public $couleur = '#000';
  public $couleurfont;
  public $app = '';
  public $site = '';
  public $auteur = '';
  public $cheminIcon = 'images/icon/';
  public $favicon = 'favicon.png';
  public $manifest = 'manifest.json';
  public $chemin = '';


  public function __construct()
  {
  }

  /**
   * Ecrire
   * nodifie le html
   */
  public function Ecrire(string $html)
  {
    if (isset($mani[1])) {
      $this->setManifest($mani[1]);
    }
    if ($this->option === []) {
      $this->setOption();
    }
    preg_match('/<link\s+rel="manifest"\s+href="(.+?)"/', $html, $mani);

    
    return $this->addBalise((string)$html);
  }

  /**
   * Fichier
   * modifie directement dans le fichier
   * @return void
   */
  public function Fichier(string $file = null)
  {
    $html = file_get_contents($file);
    preg_match('/<link\s+rel="manifest"\s+href="(.+?)"/', $html, $mani);

    if (isset($mani[1])) {
      $this->setManifest($mani[1]);
    }
    if ($this->option === []) {
      $this->setOption();
    }
    if (empty($file)) {
      $file = basename($_SERVER['PHP_SELF']);
    }

    $html = $this->addBalise((string)$html);

    file_put_contents($file, $html);
  }


  /**
   * addBalise
   * ajoute les balise manque
   * @param  mixed $html
   */
  private function addBalise(string $html)
  {
    if (!empty($this->manifest)) {
      $this->Manifest();
    }
    foreach ($this->option as $balises) {

      foreach ($balises as $id => $balise) {
        if (strpos($html, $id) !== false) {
          // Vérifier si l'élément est une balise <meta>
          $start = strpos($html, '<meta name="' . $id . '"');
          $tag = 'meta';
          // Si l'élément n'est pas une balise <meta>, vérifier s'il s'agit d'une balise <link>
          if ($start === false) {
            $start = strpos($html, '<link rel="' . $id . '"');
            $tag = 'link';
          }
          // Si la balise est trouvée, remplacer son contenu par le contenu de remplacement
          if ($start !== false) {
            $end = strpos($html, '>', $start);
            $length = $end - $start + 1;
            $html = substr_replace($html, $balise, $start, $length);
          }
        }

        if (strpos($html, $id) === false) {
          // La balise n'existe pas encore, il faut l'ajouter
          $pos = strpos($html, '</head>');
          if ($pos !== false) {
            // Insère la balise juste avant la fin de la section head
            $html = substr_replace($html, $balise . "\n", $pos, 0);
          } else {
            // La section head n'existe pas, il faut la créer
            $html = str_replace('<html>', '<html><head>' . $balise . '</head>', $html);
          }
        }
      }
    }
    
    

    return $html;
  }

  private function Detecte(): ?string
  {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    if (preg_match('/Mac/i', $user_agent)) {
      return 'ios';
    } elseif (preg_match('/iPhone/i', $user_agent)) {
      return 'ios';
    } elseif (preg_match('/iPad/i', $user_agent)) {
      return 'ios';
    } elseif (preg_match('/Droid/i', $user_agent)) {
      return 'android';
    } elseif (preg_match('/Windows/i', $user_agent)) {
      return 'windows';
    } else {
      return null;
    }
  }

  public function setSite(string $site)
  {
    $this->site = $site;
    $this->base["identifier-url"] = '<meta name="identifier-url" content="' . $this->site . '" />';
    $this->base["canonical"] = '<link rel="canonical" href="' . $this->site . '">';
  }

  public function setApp(string $app)
  {
    $this->app = $app;
    $this->base["title"] = '<meta name="title" content="' . $this->app . '" />';
    $this->base["abstract"] = '<meta name="abstract" content="' . $this->app . '" />';
  }



  public function setAuteur(string $auteur)
  {
    $this->auteur = $auteur;
    $this->base["author"] = '<meta name="author" content="' . $this->auteur . '" />';
  }

  public function setOption(array $option = [])
  {

    if ($option === []) {

      $this->option[] = $this->setMini();
      $this->option[] = $this->setBase();
      $this->option[] = $this->setAndroid();
      $this->option[] = $this->setIos();
      $this->option[] = $this->setWindows();
    } else {
      if (in_array('auto', $option)) {
        $os = $this->Detecte();
        $option = [$os];
      }
      if (in_array('mini', $option)) {
        $this->option[] = $this->setMini();
      }
      if (in_array(['base', 'android', 'ios', 'windows'], $option)) {
        $this->option[] = $this->setMini();
        $this->option[] = $this->setBase();
      }
      if (in_array('android', $option)) {
        $this->option[] = $this->setAndroid();
      }
      if (in_array('ios', $option)) {
        $this->option[] = $this->setIos();
      }
      if (in_array('windows', $option)) {
        $this->option[] = $this->setWindows();
      }
    }

    // return $this->option;
  }


  private function setMini()
  {
    $this->mini["charset=UTF-8"] = '<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />';
    $this->mini["viewport"] = '<meta name="viewport" content="width=device-width, initial-scale=1">';

    $this->mini["<title>"] = empty($this->app) ? '<title>Page</title>' : '<title>' . $this->app . '</title>';

    return $this->mini;
  }

  private function setBase()
  {
    //$this->base[] = '<!-- Other -->';
    $this->base["X-UA-Compatible"] = '<meta http-equiv="X-UA-Compatible" content="IE=edge">';

    $this->base["description"] = '<meta name="description" content="' . ($this->app ?? $this->site) . '">';
    $this->base["keywords"] = '<meta name="keywords" content="' . ($this->app ?? $this->site) . '">';

    $this->Image($this->favicon, 'favicon.ico', 16, 16, 'ico');
    copy($this->cheminIcon . 'favicon.ico', '' . basename($this->cheminIcon . 'favicon.ico'));
    $this->base["shortcut icon"] = '<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">';

    $this->base["screen-orientation"] = '<meta name="screen-orientation" content="portrait">';
    $this->base["full-screen"] = '<meta name="full-screen" content="yes">';
    $this->base["browsermode"] = '<meta name="browsermode" content="application">';
    if (!empty($this->site)) {
      $this->base["<base"] = '<base href="' . $this->site . '">';
    }

    // $this->base[] = '<!-- Disable night mode for this page  -->';
    $this->base["nightmode"] = '<meta name="nightmode" content="enable/disable">';

    //  $this->base[] = '<!-- Fitscreen  -->';
    //$this->base["uc-fitscreen=yes"] = '<meta name="viewport" content="uc-fitscreen=yes"/>';

    // $this->base[] = '<!-- Layout mode -->';
    $this->base["layoutmode"] = '<meta name="layoutmode" content="fitscreen/standard">';

    // $this->base[] = '<!-- imagemode - show image even in text only mode  -->';
    $this->base["imagemode"] = '<meta name="imagemode" content="force">';
    //  $this->base[] = '<!-- Main Link Tags  -->';
    $this->Image($this->favicon, 'favicon-16.png', 16, 16);
    $this->base["favicon-16.png"] = '<link rel="icon" href="' . $this->cheminIcon . 'favicon-16.png"  type="image/png" sizes="16x16">';
    $this->Image($this->favicon, 'favicon-32.png', 32, 32);
    $this->base["favicon-32.png"] = '<link rel="icon" href="' . $this->cheminIcon . 'favicon-32.png" type="image/png" sizes="32x32">';
    $this->Image($this->favicon, 'favicon-48.png', 48, 48);
    $this->base["favicon-48.png"] = '<link rel="icon" href="' . $this->cheminIcon . 'favicon-48.png" type="image/png" sizes="48x48">';

    $this->base["distribution"] = '<meta name="distribution" content="Global">';
    $this->base["rating"] = '<meta name="rating" content="General">';

    $this->base["pragma"] = '<meta http-equiv="pragma" content="no-cache" />';

    $this->base["language"] = '<meta name="language" content="fr-FR" />';
    $this->base["copyright"] = '<meta name="copyright" content="' .$this->auteur .' '. $this->app . '©' . date('Y') . '" />';
    $this->base["robots"] = '<meta name="robots" content="All" />';
    if (!empty($this->manifest)) {
    $this->base["manifest"] = '<link rel="manifest" href="' . $this->manifest . '">';
     }

    return $this->base;
  }

  private function setWindows()
  {
    //$this->windows[] = '<!-- Windows -->';
    if (!empty($this->couleur)) {
      $this->windows["msapplication-navbutton-color"] = '<meta name="msapplication-navbutton-color" content="' . $this->couleur . '">';
      $this->windows["msapplication-TileColor"] = '<meta name="msapplication-TileColor" content="' . $this->couleur . '">';
      // $this->Image($this->favicon, '48x48icon.png', 48, 48, 'svg');
      // $this->windows["mask-icon"] = '<link href="' . $this->cheminIcon . 'icon.svg" rel="mask-icon" size="any" color="' . $this->couleur . '">';
    }
    $this->Image($this->favicon, 'ms-icon-144x144.png', 144, 144);
    $this->windows["msapplication-TileImage"] = '<meta name="msapplication-TileImage" content="' . $this->cheminIcon . 'ms-icon-144x144.png">';
    $this->Browser(); //generer broserconfig
    $this->windows["msapplication-config"] = '<meta name="msapplication-config" content="' . $this->cheminIcon . 'browserconfig.xml">';


    if (!empty($this->app)) {
      $this->windows["application-name"] = '<meta name="application-name" content="' . $this->app . '">';
      $this->windows["msapplication-tooltip"] = '<meta name="msapplication-tooltip" content="' . $this->app . '">';
    }
    $this->windows["msapplication-tap-highlight"] = '<meta name="msapplication-tap-highlight" content="no">';
    $this->windows["msapplication-starturl"] = '<meta name="msapplication-starturl" content="/">';

    return $this->windows;
  }

  private function setAndroid()
  {
    // $this->android[] = '<!-- Android -->';
    $this->android["mobile-web-app-capable"]  = '<meta name="mobile-web-app-capable" content="yes">';
    $this->Image($this->favicon, 'icon-192x192.png', 192, 192);
    $this->android["icon-192x192.png"]  = '<link rel="icon" href="' . $this->cheminIcon . 'icon-192x192.png" sizes="192x192">';
    $this->Image($this->favicon, 'icon-128x128.png', 128, 128);
    $this->android["icon-128x128.png"]  = '<link rel="icon" href="' . $this->cheminIcon . 'icon-128x128.png" sizes="128x128">';


    if (!empty($this->couleur)) {
      $this->android["theme-color"] = '<meta name="theme-color" content="' . $this->couleur . '">';
    }
    return $this->android;
  }

  private function setIos()
  {
    // $this->ios[] = '<!-- iOS  -->';
    $this->ios["apple-mobile-web-app-capable"] = '<meta name="apple-mobile-web-app-capable" content="yes">';
    $this->ios["apple-mobile-web-app-status-bar-style"] = '<meta name="apple-mobile-web-app-status-bar-style" content="default">';

    $this->Image($this->favicon, 'touch-icon-iphone.png', 128, 128);
    $this->ios["touch-icon-iphone.png"] = '<link href="' . $this->cheminIcon . 'touch-icon-iphone.png" rel="apple-touch-icon">';

    $this->Image($this->favicon, 'touch-icon-ipad.png', 76, 76);
    $this->ios["touch-icon-ipad.png"] = '<link href="' . $this->cheminIcon . 'touch-icon-ipad.png" rel="apple-touch-icon" sizes="76x76">';

    $this->Image($this->favicon, 'touch-icon-iphone-retina.png', 120, 120);
    $this->ios["touch-icon-iphone-retina.png"] = '<link href="' . $this->cheminIcon . 'touch-icon-iphone-retina.png" rel="apple-touch-icon" sizes="120x120">';

    $this->Image($this->favicon, 'touch-icon-ipad-retina.png', 152, 152);
    $this->ios["touch-icon-ipad-retina.png"] = '<link href="' . $this->cheminIcon . 'touch-icon-ipad-retina.png" rel="apple-touch-icon" sizes="152x152">';

    $this->Image($this->favicon, 'touch-icon-start-up-320x480.png', 320, 480);
    $this->ios["touch-icon-start-up-320x480.png"] = '<link rel="apple-touch-startup-image" href="' . $this->cheminIcon . 'touch-icon-start-up-320x480.png">';

    $this->Image($this->favicon, 'icon-52x52.png', 52, 52);
    $this->ios["icon-52x52.png"] = '<link href="' . $this->cheminIcon . 'icon-52x52.png" rel="apple-touch-icon-precomposed" sizes="57x57">';

    $this->Image($this->favicon, 'icon-72x72.png', 72, 72);
    $this->ios["icon-72x72.png"] = '<link href="' . $this->cheminIcon . 'icon-72x72.png" rel="apple-touch-icon" sizes="72x72">';

    if (!empty($this->app)) {
      $this->ios["apple-mobile-web-app-title"] = '<meta name="apple-mobile-web-app-title" content="' . $this->app . '">';
    }
    return $this->ios;
  }

  public function Manifest()
  {

    if (file_exists($this->manifest)) {
      $file = fopen($this->manifest, "r+");
      $manifest = (fread($file, filesize($this->manifest)));
    } else {
      $manifest = '{}';
    }
    $manifest = json_decode($manifest, true, 512, JSON_THROW_ON_ERROR);

    if (empty($manifest["name"]) && !empty($this->app)) {
      $manifest["name"] = $this->app;
    }
    if (empty($manifest["short_name"]) && !empty($this->app)) {
      $manifest["short_name"] =  $this->app;
    }

    if (empty($manifest["related_applications"]) && !empty($this->site)) {
      $manifest["related_applications"] = ["platform" => "webapp", "url" => $this->site];
    }

    if (empty($manifest["theme_color"]) && !empty($this->couleur)) {
      $manifest["theme_color"] = $this->couleur;
    }

    if (empty($manifest["background_color"]) && !empty($this->couleurfont)) {
      $manifest["background_color"] = $this->couleurfont;
    }

    if (empty($manifest["background_color"]) && empty($this->couleurfont)) {
      $manifest["background_color"] = $this->CouleurFont($this->couleur, 0.1);
    }

    if (empty($manifest["permissions"])) {
      $manifest["permissions"] = ["gcm"];
    }

    if (empty($manifest["scope"])) {
      $manifest["scope"] = "/";
    }

    if (empty($manifest["orientation"])) {
      $manifest["orientation"] = "portrait";
    }

    if (empty($manifest["prefer_related_applications"])) {
      $manifest["prefer_related_applications"] = true;
    }

    if (empty($manifest["gcm_sender_id"])) {
      $manifest["gcm_sender_id"] = "";
    }

    if (empty($manifest["gcm_user_visible_only"])) {
      $manifest["gcm_user_visible_only"] = true;
    }

    if (empty($manifest["start_url"])) {
      $manifest["start_url"] = "/?source=pwa";
    }

    if (empty($manifest["display"])) {
      $manifest["display"] = "standalone";
    }

    if (empty($manifest["icons"])) {
      $this->Image($this->favicon, 'icon-48x48.png', 48, 48);
      $manifest["icons"][] = [
        "src" => $this->cheminIcon . "icon-48x48.png",
        "sizes" => "48x48",
        "type" => "image/png"
      ];
    }
    // verifie si les icons avec une taille definie son deja cree
    $existing_sizes = array_map(fn ($icon) => (int) explode("x", $icon["sizes"])[0], $manifest["icons"]);

    $tailles = [48, 72, 96, 144, 168, 192, 256, 512];
    foreach ($tailles as $taille) {
      if (!in_array($taille, $existing_sizes)) {
        $t = $taille . 'x' . $taille;
        $this->Image($this->favicon, "icon-" . $t . ".png", $taille, $taille);
        $manifest["icons"][] = [
          "src" => $this->cheminIcon . "icon-" . $t . ".png",
          "sizes" => $t,
          "type" => "image/png"
        ];
      }
    }
 
    file_put_contents($this->manifest, json_encode($manifest, JSON_INVALID_UTF8_IGNORE));
  }

  private function Browser()
  {
    $this->Image($this->favicon, 'icon70.png', 70, 70);
    $this->Image($this->favicon, 'icon150.png', 150, 150);
    $this->Image($this->favicon, 'icon310.png', 310, 150);
    $this->Image($this->favicon, 'icon3103.png', 310, 310);
    $browser = '<?xml version="1.0" encoding="utf-8"?>
    <browserconfig>
      <msapplication>
        <tile>
          <square70x70favicon src="icon70.png"/>
          <square150x150favicon src="icon150.png"/>
          <wide310x150favicon src="icon310.png"/>
          <square310x310favicon src="icon3103.png"/>
        </tile>
      </msapplication>
    </browserconfig>';

    file_put_contents($this->cheminIcon . 'browserconfig.xml', $browser);
  }


  public function Image(string $imgSrc, string $favicon, int $width = 16, int $height = 16, string $format = 'png', int $quality = 85)
  {
    if (!file_exists($this->cheminIcon)) {
      mkdir($this->cheminIcon, 0777, true);
    }
    $image_dest =  $this->cheminIcon . $favicon;

    $src = $imgSrc;
    if (!file_exists($image_dest)) {

      // Récupération des informations de l'image
      $image_data = getimagesize($src);
      $height1 = (int) $image_data[1];
      $width1 = (int) $image_data[0];

      // Création de l'image redimensionnée
      $dest = imagecreatetruecolor($width, $height);
      imagecopyresampled($dest, imagecreatefrompng($src), 0, 0, 0, 0, $width, $height, $width1, $height1);


      $black = imagecolorallocate($dest, 0, 0, 0);
      imagecolortransparent($dest, $black);

      // Génération de l'image selon le format demandé
      switch ($format) {
        case 'png':

          $compression = round((100 - $quality) / 10, 0);
          imagepng($dest, $image_dest, (int) $compression);
          break;

        case 'svg':
          $compression = round((100) / 10, 0);
          imagepng($dest, $image_dest, (int) $compression);
          $svg = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>' . "\n";
          $svg .= '<svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '">' . "\n";
          $svg .= '<foreignObject width="100%" height="100%">' . "\n";
          $svg .= '<div xmlns="http://www.w3.org/1999/xhtml">' . "\n";
          $svg .= '<style>img { width: 100%; height: 100%; }</style>' . "\n";
          $svg .= '<img src="' . $image_dest . '" alt="' . $favicon . '" />' . "\n";
          $svg .= '</div>' . "\n";
          $svg .= '</foreignObject>' . "\n";
          $svg .= '</svg>';

          // Écriture du contenu SVG dans le fichier de destination
          file_put_contents($image_dest, $svg);
          break;
        case 'ico':
          // Génération de l'image ICO
          $icon = imagecreatetruecolor($width, $height);
          imagecopyresampled($icon, $dest, 0, 0, 0, 0, $width, $height, $width, $height);
          imagepng($icon, $image_dest);
          break;
        default:
          return false;
      }
    }
  }

  private function CouleurFont($color, $percent)
  {
    // Convertir la couleur hexadécimale en RGB
    $rgb = sscanf($color, "#%02x%02x%02x");
    $red = $rgb[0];
    $green = $rgb[1];
    $blue = $rgb[2];

    // Calculer la nouvelle valeur de rouge, vert et bleu
    $red = round($red + ($red * $percent));
    $green = round($green + ($green * $percent));
    $blue = round($blue + ($blue * $percent));

    // Limiter les valeurs à 255
    $red = min(255, $red);
    $green = min(255, $green);
    $blue = min(255, $blue);

    // Convertir les valeurs RGB en une nouvelle couleur hexadécimale
    $new_color = sprintf("#%02x%02x%02x", $red, $green, $blue);

    // Retourner la nouvelle couleur
    return $new_color;
  }

  public function setCouleurFont(string $couleurFont)
  {
    $this->couleurfont = $couleurFont;
  }

  public function setCouleur(string $couleur)
  {
    $this->couleur = $couleur;
  }

  public function setChemin(string $chemin)
  {
    $this->chemin = $chemin;
  }

  public function setCheminIcon(string $cheminIcon)
  {
    $this->cheminIcon = $cheminIcon;
  }

  public function setManifest(string $manifest)
  {
    $this->manifest = $manifest;
  }
}
