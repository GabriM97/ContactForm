<script setup>
import axios from 'axios';


    const handleSend = function () {
        axios.post('/api/contacts', {
            name: this.name,
            surname: this.surname,
            email: this.email ?? null,
            message: this.message
        }).then(res => {
            console.log(res);
        }).catch(err => {
            console.log(err);
        })
    }
    
</script>

<template>
    <div class="mt-10">

        <div id="contact_form_container" class="flex flex-col p-5">

            <h1 class="text-3xl">Contact us</h1>

            <form action="#" id="contact_form" @submit.prevent="handleSend">
                <div class="flex flex-col md:flex-row" id="name_surname_container">

                    <!-- Name -->
                    <div class="flex flex-col my-3" id="name_container">
                        <label for="contact_form_name" class="font-medium">Name</label>
                        <input type="text" name="name" id="contact_form_name" class="border-gray-300 border-2 rounded-xl" required>
                    </div>

                    <!-- Surname -->
                    <div class="flex flex-col my-3" id="surname_container">
                        <label for="contact_form_surname" class="font-medium">Surname</label>
                        <input type="text" name="surname" id="contact_form_surname" class="border-gray-300 border-2 rounded-xl" required>
                    </div>

                </div>

                <!-- Email -->
                <div class="flex flex-col my-3" id="email_container">
                    <label for="contact_form_email" class="font-medium">Email</label>
                    <input type="email" name="email" id="contact_form_email" class="border-gray-300 border-2 rounded-xl">
                </div>

                <!-- Message -->
                <div class="flex flex-col my-3" id="message_container">
                    <label for="contact_form_message" class="font-medium">Message</label>
                    <textarea name="message" id="contact_form_message" class="border-gray-300 border-2 rounded-xl h-20" maxlength="1000" required><!-- add realtime current lenght--></textarea>
                </div>

                <!-- Buttons -->
                <div class="flex flex-row my-6 justify-center items-center" id="submit_container">
                    <input type="submit" name="send" id="contact_form_submit" value="Send" class="border-gray-300 border-2 rounded-md px-5 py-2 mr-2 w-2/3 cursor-pointer">
                    <input type="reset" name="cancel" id="contact_form_cancel" value="Cancel" class="border-gray-300 border-2 rounded-md px-5 py-2 ml-2 w-1/3 cursor-pointer">
                </div>
            </form>
        </div>
    </div>
</template>