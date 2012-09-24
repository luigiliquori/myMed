/**
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
 * 
 */
package com.mymed.utils;

import java.lang.reflect.Type;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;

import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.google.gson.JsonDeserializationContext;
import com.google.gson.JsonDeserializer;
import com.google.gson.JsonElement;
import com.google.gson.JsonParseException;
import com.google.gson.JsonPrimitive;
import com.google.gson.JsonSerializationContext;
import com.google.gson.JsonSerializer;
import com.google.gson.reflect.TypeToken;
import com.mymed.model.data.application.DataBean;
import com.mymed.model.data.application.MOntologyID;

/**
 *  Utils for Gson
 *  @author rjolivet
 */
public class GsonUtils {
    
    // ---------------------------------------------------------------------------
    // Constants
    // ---------------------------------------------------------------------------
	
	public static Type listType = new TypeToken<List<String>>() {}.getType();
	public static Type dataType = new TypeToken<Map<String, String>>() {}.getType();
	public static Type predicateType = new TypeToken<List<DataBean>>() {}.getType();
	public static Type keywordsType = new TypeToken<Map<String, List<String>>>() {}.getType();
    
    public static Gson gson;
    static{     
        gson = 
                new GsonBuilder().
                registerTypeAdapter(
                        MOntologyID.class, 
                        new MOntologyIDAdapter())
                //.setPrettyPrinting()
                .create();    
    }
    
    /** Custom serializer/deserialize for MOntologyID from/to int */
    private static class MOntologyIDAdapter implements 
        JsonSerializer<MOntologyID>, JsonDeserializer<MOntologyID> 
    {
        @Override public JsonElement serialize(
                MOntologyID ontID, 
                Type type,
                JsonSerializationContext context) 
        {
            return new JsonPrimitive(ontID.getValue());
        }
        
        @Override public MOntologyID deserialize(
                JsonElement json, 
                Type type,
                JsonDeserializationContext arg2) throws JsonParseException 
        {
            return MOntologyID.fromInt(json.getAsInt());
        }
    }
    
    
    public static Map<String, String> json_to_map(String s) {
    	return gson.fromJson(s, dataType);
    }
    public static List<String> json_to_list(String s) {
    	return gson.fromJson(s, listType);
    }
    public static List<DataBean> json_to_predicate(String s) {
    	return gson.fromJson(s, predicateType);
    }
    public static LinkedHashMap<String, List<String>> json_to_keywords(String s) {
    	return gson.fromJson(s, keywordsType);
    }
}
