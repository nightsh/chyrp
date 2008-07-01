<?php
	class Quote extends Feather {
		public function __construct() {
			$this->setField(array("attr" => "quote", "type" => "text_block", "rows" => 5, "label" => __("Quote", "quote"), "bookmarklet" => "selection"));
			$this->setField(array("attr" => "source", "type" => "text_block", "rows" => 5, "label" => __("Source", "quote"), "optional" => true, "preview" => true, "bookmarklet" => "page_title"));
			$this->setFilter("quote", "markup_post_text");
			$this->setFilter("source", "markup_post_text");
		}
		static function submit() {
			if (empty($_POST['quote']))
				error(__("Error"), __("Quote can't be empty.", "quote"));

			$values = array("quote" => $_POST['quote'], "source" => $_POST['source']);
			$clean = (!empty($_POST['slug'])) ? $_POST['slug'] : "" ;
			$url = Post::check_url($clean);

			$post = Post::add($values, $clean, $url);

			$route = Route::current();
			if (isset($_POST['bookmarklet']))
				redirect($route->url("bookmarklet/done/"));
			else
				redirect($post->url());
		}
		static function update() {
			$post = new Post($_POST['id']);

			if (empty($_POST['quote']))
				error(__("Error"), __("Quote can't be empty."));

			$values = array("quote" => $_POST['quote'], "source" => $_POST['source']);

			$post->update($values);
		}
		static function title($post) {
			return $post->title_from_excerpt();
		}
		static function excerpt($post) {
			return $post->quote;
		}
		static function add_dash($text) {
			return preg_replace("/(<p(\s+[^>]+)?>|^)/si", "\\1&mdash; ", $text, 1);
		}
		static function feed_content($post) {
			$body = "<blockquote>\n\t";
			$body.= $post->quote;
			$body.= "\n</blockquote>\n";
			$body.= $post->source;
			return $body;
		}
	}