all:
	@sh -c 'for f in *Test.php; do echo $$f; phpunit `basename $$f .php` || exit 1; done'

clean:
	rm -rf tmp
