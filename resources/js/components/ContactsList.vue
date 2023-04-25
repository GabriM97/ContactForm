<script setup>
    import ContactCard from './ContactCard.vue';
    import axios from 'axios';
    import { ref, onMounted, reactive, watch } from 'vue';

    const emit = defineEmits('errorEvent');

    const props = defineProps({
        newContactId: Number
    });

    const contacts = ref([]);

    const getContacts = function () {
        axios.get('/api/contacts', {})
            .then(res => {
                contacts.value = res.data.data;
            }).catch (err => {
                if (err.response != undefined) {
                    emit('errorEvent', err.response.data.error_message);
                    return;
                }
                emit('errorEvent', err.message);
            });
    }

    const getNewContact = function (id) {
        
        // TODO: RENDER LOADING SPINNER

        axios.get('/api/contacts/' + id, {})
            .then(res => {
                contacts.value.push(res.data.data);
            }).catch (err => {
                if (err.response != undefined) {
                    emit('errorEvent', err.response.data.error_message);
                    return;
                }
                emit('errorEvent', err.message);
            });
    }

    watch(() => props.newContactId, (id) => getNewContact(id));

    onMounted(() => {
        console.log('mounted getting new contacts');
        getContacts();
    });
</script>

<template>
    <!-- loop though all contacts -->
    <ContactCard v-for="contact in contacts" :key="contact.id" :contact="contact"
        class="my-4" />
</template>