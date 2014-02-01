<?php

namespace PhroznPlugin\Provider;
use Symfony\Component\Yaml\Yaml;
use Phrozn\Provider\Base,
    Phrozn\Provider;


class EventsCollection extends Base implements Provider {

  protected function getDefaultConfig() {
    return array(
      'config' => 'entries',
      'filter' => 'past', // "none" "past", "upcoming"
      'sort' => 'desc', // "asc", "desc"
      'limit' => '0',
    );
  }

	public function get() {
    $content = array();
    $projectPath = $this->getProjectPath();
    $config = $this->getConfig() + $this->getDefaultConfig();

    $directory = $config['directory'];
    $dir = new \RecursiveDirectoryIterator($projectPath . '/' . $directory);
    $it = new \RecursiveIteratorIterator($dir, \RecursiveIteratorIterator::SELF_FIRST);
    foreach ($it as $item) {
      if ($item->isFile()) {
        $source = file_get_contents($item->getRealPath());
        $parts = preg_split('/[\n]*[-]{3}[\n]/', $source, 2);
        if (count($parts) === 2) {
            $frontmatter = Yaml::parse($parts[0]);
            $template = trim($parts[1]);
        } else {
            $frontmatter = array();
            $template = trim($source);
        }

        $content[] = $frontmatter + array('content' => $template);
      }
    }

    // Filtering
    switch ($config['filter']) {
      case 'past':
        $content = array_filter($content, array($this, 'filter_past'));
        break;

      case 'upcoming':
        $content = array_filter($content, array($this, 'filter_upcoming'));
        break;

      default:
        break;
    }

    // Sorting
    switch ($config['sort']) {
      case 'desc':
        usort($content, array($this, 'sort_date_desc'));
        break;

      case 'asc':
        usort($content, array($this, 'sort_date_asc'));
        break;

      default:
        break;
    }

    // Limiting
    if ($config['limit'] != 0) {
      return array_slice($content, 0, $config['limit']);
    }

    $this->processContent($content);

    return $content;
	}

  /**
   * We have unprocessed permalinks, so this method was copied from Phrozn\Site\View\OutputPath\Entry\Parametrized.
   */
  protected function processContent(&$content) {
    foreach ($content as &$item) {
      $path = $permalink = $item['permalink'];

      foreach ($item as $name => $param) {
        // apply only scalar params
        if (is_scalar($param)) {
          $path = str_replace(':' . $name, $this->normalize($param), $path);
        }
      }

      // $path = rtrim($this->getView()->getOutputDir(), '/') . '/' . ltrim($path, '/');

      if (is_null($permalink) && substr($path, -5) !== '.html') {
        $path .= '.html';
      }
      error_log($path);
      $item['permalink'] = $path;
    }
  }

  /**
   * This was also copied from Phrozn\Site\View\OutputPath\Entry\Parametrized.
   */
  private function normalize($param, $space = '-') {
    $param = trim($param);
    // preserve accented chars
    if (function_exists('iconv')) {
      $param = @iconv('utf-8', 'us-ascii//TRANSLIT', $param);
    }
    $param = preg_replace('/[^a-zA-Z0-9 -]/', '', $param);
    $param = strtolower($param);
    $param = preg_replace('/[\s-]+/', $space, $param);

    return $param;
  }

  protected static function filter_past($value) {
    $event_time = strtotime($value['date']);
    $today = time();
    $diff = $today - $event_time;

    return ($diff >= 0) ? true: false;
  }

  protected static function filter_upcoming($value) {
    $event_time = strtotime($value['date']);
    $today = time();
    $diff = $today - $event_time;

    return ($diff < 0) ? true: false;
  }

  protected static function sort_date_asc($a, $b) {
    return strcmp($a['date'], $b['date']);
  }

  protected static function sort_date_desc($a, $b) {
    return  (strcmp($a['date'], $b['date'])) * -1;
  }
}
