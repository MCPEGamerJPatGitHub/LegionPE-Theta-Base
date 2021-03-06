<?php

/*
 * LegionPE Theta
 *
 * Copyright (C) 2015 PEMapModder and contributors
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PEMapModder
 */

namespace legionpe\theta\lang;

use legionpe\theta\BasePlugin;

class LanguageManager{
	/** @var Phrase[] */
	private $phrases = [];
	public function __construct(BasePlugin $plugin){
		/** @var string[] $langs */
		$langs = json_decode($plugin->getResourceContents("langs/index.json"), true);
		foreach($langs as $lang){
			$plugin->getLogger()->info("Loading language '$lang'...");
			$path = "langs/$lang.json";
			$data = json_decode($plugin->getResourceContents($path), true);
			$walker = new ArrayWalker($data);
			$list = $walker->getPlainList();
			foreach($list as $k => $v){
				$this->getPhraseObject($k)->setImplementation($lang, $v);
			}
			$plugin->getLogger()->info("Loaded language '$lang' with " . count($list) . " entries.");
		}
	}
	public function getPhraseObject($key){
		if(!isset($this->phrases[$key])){
			$this->phrases[$key] = new Phrase($key);
		}
		return $this->phrases[$key];
	}
	public function get($key, $vars, ...$langs){
		$langs[] = "en";
		return $this->phrases[$key]->get($vars, $langs);
	}
}
