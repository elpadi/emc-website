<?php
namespace MustUsePlugin;

class PostType {
	
	protected $prefix;
	protected $slug;

	public function __construct(callable $prefix, string $slug) {
		$this->prefix = $prefix;
		$this->slug = $slug;
	}

	protected function getDefaultSingular() {
		return ucwords(substr($this->slug, 0, strlen($this->slug) - 1));
	}

	public function getUrl() {
		return get_post_type_archive_link(call_user_func($this->prefix, $this->slug));
	}

	public function register($singular='', $plural='', $extra_supports=array(), $extra_settings=array()) {
		if (empty($singular)) $singular = $this->getDefaultSingular();
		if (empty($plural)) $plural = $singular.'s';
		register_post_type(call_user_func($this->prefix, $this->slug), array_merge(array(
			'public' => true,
			'label' => $plural,
			'labels' => array(
				'singular_name' => $singular,
				'add_new_item' => "Add New $singular",
			),
			'supports' => array_merge(array('title','thumbnail'), $extra_supports),
			'menu_position' => 5,
			'has_archive' => true,
			'rewrite' => array(
				'slug' => $slug,
			),
		), $extra_settings));
	}

	public function registerTaxonomy($obj_slug, $singular='', $plural='', $rewrite_slug='') {
		if (empty($singular)) $singular = $this->getDefaultSingular();
		if (empty($plural)) $plural = $singular.'s';
		if (empty($rewrite_slug)) $rewrite_slug = $this->slug;
		register_taxonomy(call_user_func($this->prefix, $this->slug), self::prefix($obj_slug), [
			'public' => true,
			'label' => $plural,
			'labels' => array(
				'singular_name' => $singular,
				'add_new_item' => "Add New $singular",
			),
			'query_var' => $this->slug,
			'rewrite' => array(
				'slug' => $rewrite_slug,
			),
		]);
		register_taxonomy_for_object_type(call_user_func($this->prefix, $this->slug), call_user_func($this->prefix, $obj_slug));
	}

}

