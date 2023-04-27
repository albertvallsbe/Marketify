<?php

namespace App\Helpers;

class ValidationMessages
{
    public static function productValidationMessages()
    {
        return [
            'product_name.required' => 'The name field is required.',
            'product_name.string' => 'The name field must be a string.',
            'product_name.max' => 'The name field may not be greater than 255 characters.',
            'product_description.required' => 'The description field is required.',
            'product_description.string' => 'The description field must be a string.',
            'product_price.required' => 'The price field is required.',
            'product_price.numeric' => 'The price field must be a number.',
            'product_price.min' => 'The price field must be at least 0.',
            'product_image.image' => 'The image field must be an image.',
            'product_image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg.',
            'product_image.max' => 'The image may not be greater than 2048 kilobytes.',
            'product_tag.string' => 'The tag field must be a string.',
            'product_category.required' => 'The category field is required.',
            'product_category.exists' => 'The selected category is invalid.',
        ];
    }

    public static function userValidationMessages()
    {
        return [
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email may not be greater than :max characters.',
            'email.unique' => 'The email has already been taken.',
            'name.required' => 'The username field is required.',
            'name.regex' => 'The username field format is invalid.',
            'name.max' => 'The username may not be greater than :max characters.',
            'name.min' => 'The username must be at least :min characters.',
            'name.unique' => 'The username has already been taken.',
            'password.required' => 'The password field is required.',
            'password.max' => 'The password may not be greater than :max characters.',
            'password.min' => 'The password must be at least :min characters.',
            'avatar.image' => 'The avatar must be an image file.',
            'avatar.mimes' => 'The avatar must be a file of type: jpeg, png, jpg, gif.',
            'avatar.max' => 'The avatar may not be greater than :max kilobytes in size.',
            'current-password.required' => 'The current password field is required.',
            'current-password.max' => 'The current password may not be greater than :max characters.',
            'current-password.min' => 'The current password must be at least :min characters.',
            
            'new-password.required' => 'The new password field is required.',
            'new-password.max' => 'The new password may not be greater than :max characters.',
            'new-password.min' => 'The new password must be at least :min characters.',
            'new-password.confirmed' => 'The new passwords does not match.',

            'repeat-password.required' => 'The new password field is required.',
            'repeat-password.max' => 'The new password may not be greater than :max characters.',
            'repeat-password.min' => 'The new password must be at least :min characters.',
            'repeat-password.same' => 'The new password and repeat password do not match.',
        ];
    }
    
    public static function shopValidationMessages()
    {
        return [
            'shopname.required' => 'The store name field is required.',
            'shopname.string' => 'The store name field must be a string.',
            'username.required' => 'The username field is required.',
            'username.string' => 'The username field must be a string.',
            'nif.required' => 'The NIF field is required.',
            'nif.string' => 'The NIF field must be a string.',
            'image.image' => 'The logo must be an image.',
            'image.mimes' => 'The logo must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The logo may not be greater than :max kilobytes.',
        ];
    }
}