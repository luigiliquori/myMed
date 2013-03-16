/*
 * Copyright 2012 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
package com.mymed.controller.core.exception;

/**
 * An myMed exception is used to convert the Exception message in a jSon format
 * for the frontend
 * 
 * @author lvanni
 */
public abstract class AbstractMymedException extends RuntimeException {

    /* --------------------------------------------------------- */
    /* Attributes */
    /* --------------------------------------------------------- */
    private static final long serialVersionUID = 1L;
    /** the status returned by the http server */
    private int status = 500;
    private final String message;

    /* --------------------------------------------------------- */
    /* Constructors */
    /* --------------------------------------------------------- */
    public AbstractMymedException(final int status, final String message) {
        super(message);
        this.status = status;
        this.message = message;
    }

    public AbstractMymedException(Throwable cause, String message) {
        super(message, cause);
        this.message = message;
    }

    /* --------------------------------------------------------- */
    /* Public methods */
    /* --------------------------------------------------------- */
    public int getStatus() {
        return status;
    }

    @Override
    public String getMessage() {
        return message;
    }
}
