<?php
/**
 * Plugin class
 */
namespace Phile\Plugin\Phile\SetupCheck;

use Phile\Core\Utility;
use Phile\Model\Page;
use Phile\Phile;
use Phile\Plugin\AbstractPlugin;

/**
 * Phile Setup Plugin Class
 *
 * @author  PhileCMS
 * @link    https://philecms.com
 * @license http://opensource.org/licenses/MIT
 * @package Phile\Plugin\Phile\PhileSetup
 */
class Plugin extends AbstractPlugin
{
    protected $needsSetup;

    /**
 * @var array event subscription
*/
    protected $events = [
        'config_loaded' => 'onConfigLoaded',
        'after_render_template' => 'onAfterRenderTemplate'
    ];

    protected function onConfigLoaded($eventData)
    {
        $this->needsSetup = empty($eventData['class']->get('encryptionKey'));
    }

    /**
     * render setup message
     *
     * @param array $eventData
     */
    protected function onAfterRenderTemplate(array $eventData)
    {
        if (!$this->needsSetup) {
            return;
        }

        $engine = $eventData['templateEngine'];

        $page = new Page($this->getPluginPath('setup.md'));
        $vars = ['encryption_key' => $this->generateToken()];
        $this->insertVars($page, $vars);

        $engine->setCurrentPage($page);
        $eventData['output'] = $engine->render();
    }

    /**
     * replace twig like variables in page content
     *
     * @param Page  $page
     * @param array $vars
     */
    protected function insertVars(Page $page, array $vars)
    {
        $content = $page->getRawContent();
        foreach ($vars as $key => $value) {
            $regex = '/\{\{(\s*?)' . $key . '(\s*?)\}\}/';
            $content = preg_replace($regex, $value, $content);
        }
        $page->setContent($content);
    }

    /**
     * generate encryption key
     *
     * @return string
     */
    protected function generateToken()
    {
        return Utility::generateSecureToken(64);
    }
}
