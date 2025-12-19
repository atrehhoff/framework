<?php

namespace EventListeners;

class BaseurlRedirect {
	/**
	 * Make sure client is using the configured baseurl.
	 * Implicitly also handles HTTPS redirects if set.
	 *
	 * @return void
	 */
	public function handle(): void {
		if(IS_CLI) return;

		$baseurl = \Url::getBaseurl();
		$urlparts = \Url::parse($baseurl);

		// Build the target URL using baseurl's
		// scheme, host, port, and path, but keep the current request URI
		$target = $urlparts['scheme'] . '://' . $urlparts['host'];
		if (($urlparts['port'] ?? null) !== null && !(
			($urlparts['scheme'] === 'https' && $urlparts['port'] == 443) ||
			($urlparts['scheme'] === 'http' && $urlparts['port'] == 80)
		)) {
			$target .= ':' . $urlparts['port'];
		}

		// Ensure baseurl path (if any) is prefixed
		$basePath = \Str::rtrim($urlparts['path'] ?? '', '/');
		$requestUri = $_SERVER['REQUEST_URI'] ?? '/';

		// Remove basePath from requestUri only if it is at
		// the very start and not already part of the base path
		if ($basePath && \Str::pos($requestUri, $basePath . '/') === 0) {
			$requestUri = \Str::substr($requestUri, \Str::len($basePath));
		}

		// Ensure there is exactly one slash between basePath and requestUri
		$target .= \Str::rtrim($basePath, '/') . '/' . \Str::ltrim($requestUri, '/');
		$current = ($_SERVER['REQUEST_SCHEME'] ?? 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		if ($current !== $target) {
			\Url::redirect($target, __METHOD__);
		}
	}
}
