<?php
// Copyright 2004-present Facebook. All Rights Reserved.
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//   http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

namespace facebook\Selenium\phpWebDriver\interactions;

use facebook\Selenium\phpWebDriver\WebDriver;
use facebook\Selenium\phpWebDriver\interactions\internal\WebDriverClickAction;
use facebook\Selenium\phpWebDriver\interactions\internal\WebDriverContextClickAction;
use facebook\Selenium\phpWebDriver\interactions\internal\WebDriverClickAndHoldAction;
use facebook\Selenium\phpWebDriver\interactions\internal\WebDriverDoubleClickAction;
use facebook\Selenium\phpWebDriver\interactions\internal\WebDriverMouseMoveAction;
use facebook\Selenium\phpWebDriver\interactions\internal\WebDriverButtonReleaseAction;
use facebook\Selenium\phpWebDriver\interactions\internal\WebDriverMoveToOffsetAction;
use facebook\Selenium\phpWebDriver\interactions\internal\WebDriverKeyDownAction;
use facebook\Selenium\phpWebDriver\interactions\internal\WebDriverKeyUpAction;
use facebook\Selenium\phpWebDriver\interactions\internal\WebDriverSendKeysAction;
use facebook\Selenium\phpWebDriver\WebDriverElement;

/**
 * WebDriver action builder. It implements the builder pattern.
 */
class WebDriverActions {

  protected $driver;
  protected $keyboard;
  protected $mouse;
  protected $action;

  public function __construct(WebDriver $driver) {
    $this->driver = $driver;
    $this->keyboard = $driver->getKeyboard();
    $this->mouse = $driver->getMouse();
    $this->action = new WebDriverCompositeAction();
  }

  /**
   * A convenience method for performing the actions without calling build().
   */
  public function perform() {
    $this->action->perform();
  }

  /**
   * Mouse click.
   * If $element is provided, move to the middle of the element first.
   */
  public function click(WebDriverElement $element = null) {
    $this->action->addAction(
      new WebDriverClickAction($this->mouse, $element)
    );
    return $this;
  }

  /**
   * Mouse click and hold.
   * If $element is provided, move to the middle of the element first.
   */
  public function clickAndHold(WebDriverElement $element = null) {
    $this->action->addAction(
      new WebDriverClickAndHoldAction($this->mouse, $element)
    );
    return $this;
  }

  /**
   * Context-click (right click).
   * If $element is provided, move to the middle of the element first.
   */
  public function contextClick(WebDriverElement $element = null) {
    $this->action->addAction(
      new WebDriverContextClickAction($this->mouse, $element)
    );
    return $this;
  }

  /**
   * Double click.
   * If $element is provided, move to the middle of the element first.
   */
  public function doubleClick(WebDriverElement $element = null) {
    $this->action->addAction(
      new WebDriverDoubleClickAction($this->mouse, $element)
    );
    return $this;
  }

  /**
   * Drag and drop from $source to $target.
   */
  public function dragAndDrop(WebDriverElement $source,
                              WebDriverElement $target) {
    $this->action->addAction(
      new WebDriverClickAndHoldAction($this->mouse, $source)
    );
    $this->action->addAction(
      new WebDriverMouseMoveAction($this->mouse, $target)
    );
    $this->action->addAction(
      new WebDriverButtonReleaseAction($this->mouse, $target)
    );
    return $this;
  }

  /**
   * Drag $source and drop by offset ($x_offset, $y_offset).
   */
  public function dragAndDropBy(WebDriverElement $source,
                                $x_offset,
                                $y_offset) {
    $this->action->addAction(
      new WebDriverClickAndHoldAction($this->mouse, $source)
    );
    $this->action->addAction(
      new WebDriverMoveToOffsetAction($this->mouse, null, $x_offset, $y_offset)
    );
    $this->action->addAction(
      new WebDriverButtonReleaseAction($this->mouse, null)
    );
    return $this;
  }

  /**
   * Mouse move by offset.
   */
  public function moveByOffset($x_offset, $y_offset) {
    $this->action->addAction(
      new WebDriverMoveToOffsetAction($this->mouse, null, $x_offset, $y_offset)
    );
    return $this;
  }

  /**
   * Move to the middle of the given WebDriverElement. If offset are provided,
   * move the an offset from the top-left cornerof that element.
   */
  public function moveToElement(WebDriverElement $element,
                                $x_offset = null,
                                $y_offset = null) {
    $this->action->addAction(new WebDriverMoveToOffsetAction(
      $this->mouse, $element, $x_offset, $y_offset
    ));
    return $this;
  }

  /**
   * Release the mouse button.
   * If $element is provided, move to the middle of the element first.
   */
  public function release(WebDriverElement $element = null) {
    $this->action->addAction(
      new WebDriverButtonReleaseAction($this->mouse, $element)
    );
    return $this;
  }

  /**
   * Press a key on keyboard.
   * If $element is provided, focus on that element first.
   *
   * @see WebDriverKeys for special keys like CONTROL, ALT, etc.
   */
  public function keyDown(WebDriverElement $element = null, $key) {
    $this->action->addAction(
      new WebDriverKeyDownAction($this->keyboard, $this->mouse, $element, $key)
    );
    return $this;
  }

  /**
   * Release a key on keyboard.
   * If $element is provided, focus on that element first.
   *
   * @see WebDriverKeys for special keys like CONTROL, ALT, etc.
   */
  public function keyUp(WebDriverElement $element = null, $key) {
    $this->action->addAction(
      new WebDriverKeyUpAction($this->keyboard, $this->mouse, $element, $key)
    );
    return $this;
  }

  /**
   * Send keys by keyboard.
   * If $element is provided, focus on that element first.
   *
   * @see WebDriverKeys for special keys like CONTROL, ALT, etc.
   */
  public function sendKeys(WebDriverElement $element = null, $keys) {
    $this->action->addAction(
      new WebDriverSendKeysAction(
        $this->keyboard, $this->mouse, $element, $keys
      )
    );
    return $this;
  }
}
