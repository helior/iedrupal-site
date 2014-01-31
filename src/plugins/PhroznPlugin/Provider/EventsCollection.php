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

    return $content;
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
