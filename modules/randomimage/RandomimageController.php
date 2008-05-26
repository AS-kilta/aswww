<?php
//include_once 'modules/frontpage/Frontpage.php';

class RandomimageController extends ModuleController {
    function __construct() {
        $this->moduleName = 'randomimage';
    }

    function renderDefault() {
        global $_;  // Translation strings

        // Load imagelist
        /*
        $list = Array();
        if ($directory = opendir($_['dir'])) {
            while (false !== ($file = readdir($directory))) {
                if (is_file($_['dir'] . $file) && $file{0} != ".") {
                    $list[] = $_['dir'] . $file;
                }
            }

            closedir($directory);
        }
        */

        // Which branch is selected
        $navi = Navi::getInstance();
        $node = $navi->getNaviTree()->getSelectedBranch();
        if ($node != null) {
            $title = $node->getTitle(getLanguage());
        } else {
            $title = 'default';
        }

        // Select image
        // (XXX: Dirty hack)
        switch ($title) {
            case 'Etusivu':
            case 'Frontpage':
                $fileName = 'wappu.jpg';
                break;

            case 'Guild':
            case 'Kilta':
                $fileName = 'kokkarit.jpg';
                break;

            case 'Opiskelu':
            case 'Studying':
                $fileName = 'tuas.jpg';
                break;

            case 'Phuksit':
            case 'Freshmen':
                $fileName = 'vesisota.jpg';
                break;

            default:
                $fileName = 'paa.jpg';
        }

        $html = '<img alt="Banner" src="' . baseUrl() . '/' . $_['dir'] . $fileName . '" />';

        return $html;
    }

}

?>
