<?php

namespace RockLobsterInc\FormDataTree;


/**
 * Returns components of the given name.
 *
 * @param string $name Field name, such as 'abc', 'abc[de]', or 'abc[]'.
 * @return array Single dimension array of name components.
 */
function dissolve_name( string $name ): array {
	$name = trim( $name );

	if ( '' === $name ) {
		return [];
	}

	$first_bracket = strpos( $name, '[' );

	if ( false === $first_bracket ) {
		return [ $name ];
	}

	$core = trim( substr( $name, 0, $first_bracket ) );

	if ( '' === $core ) {
		return [];
	}

	$dimensions = substr( $name, $first_bracket );

	preg_match_all( '/\[(.*?)\]/', $dimensions, $matches );

	return array_map( 'trim', [ $core, ...$matches[1] ] );
}


/**
 * Converts a scalar value into a map with a specified key. The original
 * array structure will be preserved.
 *
 * @param string $key Map key.
 * @param mixed $value Original value.
 * @return array Array.
 */
function scalar_to_map( string $key, mixed $value ): array {
	if ( is_scalar( $value ) ) {
		return [ $key => $value ];
	}

	if ( is_array( $value ) ) {
		return array_map( static function ( $item ) use ( $key ) {
			return scalar_to_map( $key, $item );
		}, $value );
	}

	return [];
}
