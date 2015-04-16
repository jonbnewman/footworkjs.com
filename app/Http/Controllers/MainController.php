<?php namespace App\Http\Controllers;

use Helper\Docs;
use Helper\Builds;
use \Mobile_Detect;

class MainController extends Controller {
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct() {
    $this->middleware('guest');
  }

  public function index() {
    $docs = new Docs();
    $builds = new Builds();
    $detect = new Mobile_Detect();

    $isMobile = false;
    $isTablet = false;
    $isIOS = false;
    $IOSVersion = null;
    if($detect->isMobile() && !$detect->isTablet()) {
      $isMobile = true;
    }
    if($detect->isTablet()) {
      $isTablet = true;
    }
    if($detect->isiOS()) {
      $isIOS = true;
      $IOSVersion = $detect->version('iPhone');
      if(empty($version)) {
        $IOSVersion = $detect->version('iPad');
      }
    }

    $buildVersion = 0;
    if(getenv('APP_ENV') !== 'local') {
      $redis = \Illuminate\Support\Facades\Redis::connection();
      $buildVersion = $redis->get('buildVersion');
      if(empty($buildVersion)) {
        $package = json_decode(file_get_contents(base_path().'/package.json'), true);
        $buildVersion = $package['buildVersion'];
        $redis->set('buildVersion', $buildVersion);
      }
    } else {
      $package = json_decode(file_get_contents(base_path().'/package.json'), true);
      $buildVersion = $package['buildVersion'];
    }

    return view('welcome')->with([
      'isMobile' => $isMobile,
      'isTablet' => $isTablet,
      'isIOS' => $isIOS,
      'IOSVersion' => $IOSVersion,
      'og' => [
        'title' => 'footwork.js',
        'description' => 'A solid footing for web applications.',
        'url' => "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}",
        'image' => ""
      ],
      'title' => 'footwork.js',
      'siteName' => 'footwork.js',
      'buildVersion' => $buildVersion,
      'docNavData' => json_encode($docs->navData()),
      'releaseList' => json_encode($builds->getReleases(getenv('FOOTWORK_RELEASES_FOLDER')))
    ]);
  }
}
