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

//#include <math.h>
#include <exception>

#include "MovingBuffer.hpp"
// todo: yuuch.  but I was unable to force the instation in another file
#include "Message.hpp"



namespace utils {

    static bool Verbose = 0;
    
    template<class T>
    MovingBuffer<T>::MovingBuffer(int bufferSize) :
    bufferSize(bufferSize),
    firstIndex(0),
    buffer(bufferSize) {
    }

    template<class T>
    T* MovingBuffer<T>::get(int index) {
        //if (Verbose) std::cerr<< " MovingBuf get" << std::endl;
        int adjIx = getAdjustedIndex(index);
        if (adjIx == -1) return NULL;
        else return buffer.at(adjIx);
        //return buffer[getAdjustedIndex(index)];
    }

    template<class T>
    void MovingBuffer<T>::put(T* newElement, int index) {
        if (Verbose) std::cerr << " putting elt in " << index << " before " << *this;
        try {
            int adjIx = getAdjustedIndex(index);
            if (adjIx != -1) buffer.at(adjIx) = newElement;
        } catch (int error) {
            if (Verbose) std::cerr << " over/underflow error in put, noop" << std::endl;
        }
        if (Verbose) std::cerr << " after " << *this <<std::endl;
    }

    template<class T>
    T* MovingBuffer<T>::pop() {
        if (Verbose) std::cerr << "before pop " << *this << std::endl;
        T* firstElement = buffer[0];
        buffer.erase(buffer.begin());
        buffer.push_back(NULL);
        firstIndex++;
        if (Verbose) std::cerr << "after pop " << *this << std::endl;
        return firstElement;
    }

    template<class T>
    T* MovingBuffer<T>::popIfNotEmpty() {
        if (Verbose) std::cerr << " size is " << buffer.size();
        if (buffer.at(0) == NULL) {
            if (Verbose) std::cerr << " non c'e" << std::endl;
            return NULL;
        }
        else {
            if (Verbose) std::cerr << " popping" << std::endl;
            return pop();
        }
    }

    template<class T>
    int MovingBuffer<T>::getFirstIndex() const {
        return firstIndex;
    }

    template<class T>
    int MovingBuffer<T>::getAdjustedIndex(int index) {
        int adjustedIndex = index - firstIndex;
        //if (Verbose) std::cerr << " gadjIx of " << index << " firstIx " << firstIndex << " adj " << adjustedIndex << std::endl;
        //if (adjustedIndex < 0) throw MovingBuffer_IndexUnderflow;
        //if (adjustedIndex >= bufferSize) throw MovingBuffer_IndexOverflow;
        if (adjustedIndex < 0 || adjustedIndex >= bufferSize) return -1;
        return adjustedIndex;
    }

    template class MovingBuffer<int>;
    template class MovingBuffer<PseudoTcp::Message>;

}


