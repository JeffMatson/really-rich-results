<?php

class HomePageSchemaCest {

	public function schemaTagExists( AcceptanceTester $I ) {
		$I->amOnPage( '/' );
		$I->seeInSource( '<script type="application/ld+json">' );
	}
}