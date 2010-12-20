<?php
	Pie_Easy_Loader::load( 'docs' );
	Pie_Easy_Docs::create( INFINITY_ADMIN_DOCS_DIR . DIRECTORY_SEPARATOR . 'docs', 'index' )->publish();
?>