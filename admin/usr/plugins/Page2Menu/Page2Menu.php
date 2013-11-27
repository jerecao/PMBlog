<?php
/**
 * use $menu variable in template to show menu
 * How To use it?
 * 1. Add MenuItem in the Page2Menu/menus.html. One line just contain only one MenuItem like [MenuTitle](Menulink "MenuDescription")
 * 2. Use {{menu}} in your template, for example:
 * {% for item in menu %}
 * 		{{item.title}}	{# MenuItem's title #}
 *		{{item.url}}	{# MenuItem's url #}
 *		{{item.description}}	{# MenuItem's description #}
 * {% endfor %}
 *
 * @author lizheming
 * @link http://github.com/lizheming
 * @version 0.1.0
 */

class Page2Menu {
	private $menu;

    public function config_loaded(&$site) {
    	$file = $site['config']['plugins'].'/Page2Menu/menus.html';
    	if(!file_exists($file)) return false;
    	$text = file_get_contents($file);
    	$preg = '/^\[(.*?)\]\((.*?)\)/m';
    	preg_match_all($preg, $text, $match);
		$menu = array();
		foreach($match[2] as $k => $v)
		{
			if(preg_match('/\'/', $v)) {
				$t = explode(' \'', $v);
			} elseif(preg_match('/ "/', $v)) {
				$t = explode(' "', $v);
			} else {
				$t = array($v, '');
			}
			$menu[] = array('title' => $match[1][$k], 'url' => $t[0], 'description' => $t[1]);
		}
		$this->menu = $menu;
		return $menu;
    }

    public function after_get_variables(&$variables)
    {
    	$variables['menu'] = $this->menu;
    }
}