<?php

namespace RockLobsterInc\FormDataTree;


/**
 * Returns components of the given name.
 *
 * @param string $name Field name, such as 'abc', 'abc[de]', or 'abc[]'.
 * @return array Single dimension array of name components.
 */
function dissolve_name( string $name ): array {
	$name = str_replace( [ ' ', "\t", "\n", "\r", "\0", "\v" ], '', $name );

	if ( '' === $name ) {
		return [];
	}

	$pattern = '/^([a-z][0-9a-z:_-]*)((?:\[[a-z][0-9a-z:_-]*\])*)$/i';

	if ( ! preg_match( $pattern, $name, $matches ) ) {
		return [];
	}

	$core = $matches[ 1 ];
	$layers = $matches[ 2 ];

	preg_match_all( '/\[([a-z][0-9a-z:_-]*)\]/i', $layers, $matches );

	return [ $core, ...$matches[ 1 ] ];
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
