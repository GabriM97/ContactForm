<script setup>
    import ContactCard from './ContactCard.vue';
    import Spinner from './Spinner.vue';
    import axios from 'axios';
    import { ref, onMounted, reactive, watch } from 'vue';

    const emit = defineEmits(['errorEvent']);

    const props = defineProps({
        newContactId: Number
    });

    const contacts = ref([]);
    const contactsListDiv = ref(null);

    const smallDevicesClass = ref('');

    const showLoadingSpinner = ref(false);
    const opacityClass = ref('');

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
        toggleSpinner();

        axios.get('/api/contacts/' + id, {})
            .then(res => {
                addNewContact(res.data.data);
            }).catch (err => {
                if (err.response != undefined) {
                    emit('errorEvent', err.response.data.error_message);
                    return;
                }
                emit('errorEvent', err.message);
            }).finally(() => {
            toggleSpinner();
        });
    }

    const addNewContact = (newContact) => {
        let contactsCopy = Array.from(contacts.value);
        let first = contactsCopy.shift();

        if (contacts.value < 1 || !first.bookmark) {
            contacts.value.unshift(newContact);
            contactsListDiv.value.scroll({left: 0, behavior: 'smooth'});
            return;
        } 

        contactsCopy.unshift(newContact);
        contactsCopy.unshift(first);
        contacts.value = contactsCopy;
        contactsListDiv.value.scroll({left: 0, behavior: 'smooth'});
    }

    const removeContact = (contactId) => {
        contacts.value = contacts.value.filter(contact => contact.id != contactId);
    }

    const toggleBookmarkContact = (contactId) => {
        getContacts();  // implement client-side solution instead of quering the server 
    }

    const toggleStarContact = (contactId) => {
        getContacts(); // implement client-side solution instead of quering the server
    }

    const toggleSpinner = function () {
        showLoadingSpinner.value = !showLoadingSpinner.value;
        opacityClass.value = opacityClass.value === '' ? 'opacity-50' : '';
    }

    watch(() => props.newContactId, (id) => getNewContact(id));
    
    watch(contacts, (newContacts) => {
        smallDevicesClass.value = '';
        if (newContacts.length <= 1) {
            smallDevicesClass.value = 'max-sm:justify-center';
        }
        
        if (newContacts.length < 6) {
            smallDevicesClass.value = 'sm:justify-center';
        }
    });

    onMounted(() => {
        getContacts();
        
        // clear error messages when mounted
        emit('errorEvent', '');
    });

</script>

<template>
    <h1 class="text-3xl p-5 my-5 font-bold mb-5">Your contacts</h1>

    <Spinner v-if="showLoadingSpinner" />

    <div class="flex flex-nowrap relative overflow-x-auto gap-x-10 p-5 mb-5" :class="smallDevicesClass + ' ' + opacityClass" ref="contactsListDiv">
        <!-- loop though all contacts -->
        <TransitionGroup name="contacts"> 
            <ContactCard v-for="contact in contacts" :key="contact.id" :contact="contact"
                @contactDeleted="(contactId) => removeContact(contactId)" 
                @contactBookmark="(contactId) => toggleBookmarkContact(contactId)"
                @contactStar="(contactId) => toggleStarContact(contactId)"
            />
        </TransitionGroup> 
    </div>
</template>