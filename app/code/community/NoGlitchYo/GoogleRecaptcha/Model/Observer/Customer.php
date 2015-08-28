<?php
/**
 * NOTICE OF LICENSE
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR
 * THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @category NoGlitchYo
 * @package NoGlitchYo_Google_Recaptcha
 * @author Maxime ELOMARI <maxime.elomari@gmail.com>
 * @copyright Copyright (c) 2015, Maxime Elomari
 * @license http://opensource.org/licenses/MIT
 */

class NoGlitchYo_GoogleRecaptcha_Model_Observer_Customer
{
    protected function _getValidator()
    {
        return Mage::getSingleton('grecaptcha/validator');
    }

    /**
     * Check Google Recaptcha on User Login Page
     *
     * @param $observer
     */
    public function validateLogin($observer)
    {
        $controller = $observer->getControllerAction();

        if ( ! $this->_getValidator()->validate($controller)) {
            Mage::getSingleton('customer/session')->addError(Mage::helper('grecaptcha')->__('Incorrect reCAPTCHA.'));
            $controller->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
            $beforeUrl = Mage::getSingleton('customer/session')->getBeforeAuthUrl();
            $url =  $beforeUrl ? $beforeUrl : Mage::helper('customer')->getLoginUrl();
            $controller->getResponse()->setRedirect($url);
        }

        return $this;
    }

    public function validateCreate($observer)
    {
        $controller = $observer->getControllerAction();

        if ( ! $this->_getValidator()->validate($controller)) {
            Mage::getSingleton('customer/session')->addError(Mage::helper('captcha')->__('Incorrect reCAPTCHA.'));
            $controller->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
            Mage::getSingleton('customer/session')->setCustomerFormData($controller->getRequest()->getPost());
            $controller->getResponse()->setRedirect(Mage::getUrl('*/*/create'));
        }

        return $this;
    }

    public function validateForgotPassword($observer)
    {

    }
}
