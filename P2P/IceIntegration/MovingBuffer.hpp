/*
 * Copyright 2012 POLITO 
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
/* 
 * File:   MovingBuffer.hpp
 * Author: user
 *
 * Created on July 5, 2011, 10:33 AM
 */

#ifndef MOVINGBUFFER_HPP
#define	MOVINGBUFFER_HPP

#include <vector>
#include <iostream>

/// The utils namespace encapsulates some useful ADTs
namespace utils {

    /// MovingBuffer is a data structure useful for PseudoTcpRx
    /**
     * A MovingBuffer is useful for keeping track of sequentially numbered
     * items which may not arrive in order.  The items are partitioned into
     * three groups:
     * -# 0-(n-1): First n contiguous items.
     * -# n ... n + bufferSize: Window in which out-of-sequence items may be added.
     * -# the rest (numbers above this can't be handled if they arrive, they
     * exceed the buffer size.
     *
     * Use by PseudoTcpRx: PseudoTcpRx utilizes a moving buffer to maintain
     * the Message objects received.  Given uncertainties in the underlying Connection,
     * there may be gaps.  This data structure is useful for keeping track of
     * which Messages have arrived.  
     *
     * @todo: perhaps convert vector to deque.  explain the implementation a bit.
     * There are two parts to the structure.  A buffer holds numbered items.
     * An integer represents the first index of the buffer.  This also implicitly
     * tells us that the first (firstIndex - 1) items have all arrived.  The user
     * will probably want to remove items as soon as the first gap is filled,
     * that is why there is a popIfNotEmpty function.
     */
    template <class T>
    class MovingBuffer {
    public:

        /// Initialze with specified buffer size.
        MovingBuffer(int bufferSize);

        /// Retreive an element.  The index should be 'absolute'.
        /// Can only have a return value for objects which are in
        /// the current buffer.
        T* get(int index);
        /// Add a new element into the structure at the given absolute index.
        void put(T* newElement, int index);
        /// Removes (and returns) the first element of the buffer, and 'shifts'
        /// the buffer window.
        T* pop();
        /// pop iff the first element is not NULL.
        T* popIfNotEmpty();
        /// accessor for FirstIndex property
        int getFirstIndex() const;

        friend std::ostream & operator<<(std::ostream& o, MovingBuffer const& mb) {
            o << "[";
            if (mb.firstIndex == 1) o << "0";
            else if (mb.firstIndex > 1) o << "0.." << mb.firstIndex - 1;
            o << "(";
            for (int i = 0; i < mb.bufferSize; i++) {
                (mb.buffer.at(i) == NULL ? o << "__" : o << i + mb.firstIndex);
                o << (i == mb.bufferSize - 1 ? "" : ",");
            }
            return o << ")]";
        }

        static const int MovingBuffer_IndexUnderflow = -1;
        static const int MovingBuffer_IndexOverflow = -2;

    private:
        int bufferSize;
        int firstIndex;
        std::vector<T*> buffer;

        /// Converts an 'absolute' index into an actual index into the buffer.
        int getAdjustedIndex(int index);

    };

}

#endif	/* MOVINGBUFFER_HPP */

