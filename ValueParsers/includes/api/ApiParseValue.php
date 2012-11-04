<?php

namespace ValueParsers;
use ApiBase, MWException;

/**
 * API module for using value parsers.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @since 0.1
 *
 * @file
 * @ingroup ValueParsers
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ApiParseValue extends ApiBase {

	/**
	 * @see ApiBase::execute
	 *
	 * @since 0.1
	 */
	public function execute() {
		$params = $this->extractRequestParams();

		$parser = ValueParserFactory::singleton()->getParser( $params['parser'] );

		// Paranoid check, should never fail as we only accept registered parsers for the parser parameter.
		if ( $parser === null ) {
			throw new MWException( 'Could not obtain a ValueParser instance' );
		}

		$results = array();

		foreach ( $params['values'] as $value ) {
			$parseResult = $parser->parse( $value );

			$result = array(
				'raw' => $value
			);

			if ( $parseResult->isValid() ) {
				$dataValue = $parseResult->getDataValue();

				$result['value'] = $dataValue->getArrayValue();
				$result['type'] = $dataValue->getType();
			}
			else {
				$result['error'] = $parseResult->getError()->getText();
			}

			$results[] = $result;
		}

		$this->getResult()->setIndexedTagName( $results, 'result' );

		$this->getResult()->addValue(
			null,
			'results',
			$results
		);
	}

	/**
	 * @see ApiBase::getAllowedParams
	 *
	 * @since 0.1
	 *
	 * @return array
	 */
	public function getAllowedParams() {
		return array(
			'parser' => array(
				ApiBase::PARAM_TYPE => ValueParserFactory::singleton()->getParserIds(),
				ApiBase::PARAM_REQUIRED => true,
			),
			'values' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true,
				ApiBase::PARAM_ISMULTI => true,
			),
			// TODO: options
		);
	}

	/**
	 * @see ApiBase::getParamDescription
	 *
	 * @since 0.1
	 *
	 * @return array
	 */
	public function getParamDescription() {
		return array(
			'parser' => 'Id of the ValueParser to use',
			'values' => 'The values to parse',
		);
	}

	/**
	 * @see ApiBase::getDescription
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getDescription() {
		return array(
			'API module for parsing values using a ValueParser.'
		);
	}

	/**
	 * @see ApiBase::getExamples
	 *
	 * @since 0.1
	 *
	 * @return array
	 */
	protected function getExamples() {
		return array(
			// 'ex' => 'desc' // TODO
		);
	}

	/**
	 * @see ApiBase::getHelpUrls
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getHelpUrls() {
		return ''; // TODO
	}

	/**
	 * @see ApiBase::getVersion
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getVersion() {
		return __CLASS__ . '-' . ValueParsers_VERSION;
	}

}
