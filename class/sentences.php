<?php
/**
 * Chronolabs REST Whois API
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Cooperative http://labs.coop
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         whois
 * @since           1.0.2
 * @author          Simon Roberts <meshy@labs.coop>
 * @version         $Id: apiserver.php 1000 2013-06-07 01:20:22Z mynamesnot $
 * @subpackage		api
 * @description		Whois API Service REST
 */

/**
 * API Server Class Factory
 *
 * @author     Simon Roberts <meshy@labs.coop>
 * @package    whois
 * @subpackage api
 */
class APISentences {
	
    /**
     * var string
     */
    var $_message = '';
    
    /**
     * var integer
     */
    var $_sentences = 0;
	
    /**
     * var integer
     */
    var $_headers = 0;
    
    /**
     * var integer
     */
    var $_questions = 0;
    
    /**
     * var integer
     */
    var $_exclamations = 0;
    
    /**
     * var integer
     */
    var $_words = 0;
    
    /**
     * var integer
     */
    var $_spammers = 0;
    
    /**
     * var integer
     */
    var $_hammers = 0;
    
    /**
     * var integer
     */
    var $_crassifications = 0;
    
    /**
     * var integer
     */
    var $_breaths = 0;
    
    /**
     * var float
     */
    var $_alpha = 0;
    
    /**
     * var float
     */
    var $_gammer = 0;
    
    /**
     * Class Constructor
     * 
     * @param string $message
     */
	function __construct($message = '') {
	    if ($message != $this->setMessage($message))
	        die("Fatal: Message Corrupted in Sentences Class Constructor");
	}
	

	/**
	 * Function for setting primary message for calculations
	 * 
	 * @param string $message
	 * @return string
	 */
	public function setMessage($message = '') {
	    return $this->_message = $message;
	}
	
	/**
	 * Adds additional count to the class variables
	 * 
	 * @param number $num
	 * @return number
	 */
	public function addSenences($num = 1) {
	    return $this->_sentences = $this->_sentences + $num;
	}
	
	/**
	 * Adds additional count to the class variables
	 *
	 * @param number $num
	 * @return number
	 */
	public function addHeaders($num = 1) {
	    return $this->_headers = $this->_headers + $num;
	}
	
	/**
	 * Adds additional count to the class variables
	 *
	 * @param number $num
	 * @return number
	 */
	public function addQUestions($num = 1) {
	    return $this->_questions = $this->_questions + $num;
	}
	
	/**
	 * Adds additional count to the class variables
	 *
	 * @param number $num
	 * @return number
	 */
	public function addExclamations($num = 1) {
	    return $this->_exclamations = $this->_exclamations + $num;
	}
	
	/**
	 * Adds additional count to the class variables
	 *
	 * @param number $num
	 * @return number
	 */
	public function addWords($num = 1) {
	    return $this->_words = $this->_words + $num;
	}
	
	/**
	 * Adds additional count to the class variables
	 *
	 * @param number $num
	 * @return number
	 */
	public function addSpammers($num = 1) {
	    return $this->_spammers = $this->_spammers + $num;
	}
	
	/**
	 * Adds additional count to the class variables
	 *
	 * @param number $num
	 * @return number
	 */
	public function addHammers($num = 1) {
	    return $this->_hammers = $this->_hammers + $num;
	}
	
	/**
	 * Adds additional count to the class variables
	 *
	 * @param number $num
	 * @return number
	 */
	public function addCrassifications($num = 1) {
	    return $this->_crassifications = $this->_crassifications + $num;
	}
	
	/**
	 * Adds additional count to the class variables
	 *
	 * @param number $num
	 * @return number
	 */
	public function addBreaths($num = 1) {
	    return $this->_breaths = $this->_breaths + $num;
	}
	
	/**
	 * Adds additional count to the class variables
	 *
	 * @param number $num
	 * @return number
	 */
	public function addAlpha($num = 1) {
	    return $this->_alpha = $this->_alpha + $num;
	}
	
	/**
	 * Adds additional count to the class variables
	 *
	 * @param number $num
	 * @return number
	 */
	public function addGamma($num = 1) {
	    return $this->_gamma = $this->_gamma + $num;
	}
	
	/**
	 * Get to the total count array
	 *
	 * @return array
	 */
	public function getCountArray() {
	    return     array(   'sentences'        =>      $this->_sentences,
	                        'headers'          =>      $this->_headers,
                	        'questions'        =>      $this->_questions,
                	        'exclamations'     =>      $this->_exclamations,
                	        'words'            =>      $this->_words,
                	        'spammers'         =>      $this->_spammers,
                	        'hammers'          =>      $this->_hammers,
                	        'crassification'   =>      $this->_crassification,
                	        'breaths'          =>      $this->_breaths,
	                        'alpha'            =>      $this->_alpha,
	                        'gamma'            =>      $this->_gamma);
	}
}