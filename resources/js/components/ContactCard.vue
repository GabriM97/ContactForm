<script setup>
    import axios from 'axios';
    import { ref, onMounted } from 'vue';

    const emit = defineEmits('errorEvent', 'contactDeleted')

    const props = defineProps({
        contact: Object
    })

    const displayClass = ref('');
    // const displayClass = ref('visible opacity-1 transition-visibility');

    const handleDelete = function () {
        axios.delete('/api/contacts/' + props.contact.id, {})
            .then(res => {
                // displayClass.value = 'hidden opacity-0 transition-visibility transition-opacity';
                emit('contactDeleted', res.data.data.id)
            }).catch (err => {
                if (err.response != undefined) {
                    emit('errorEvent', err.response.data.error_message);
                    return;
                }
                emit('errorEvent', err.message);
            });
    };

</script>

<template>
    <div class="flex flex-col min-w-[70%] max-w-[70%] sm:max-w-[50%] sm:min-w-[50%] md:max-w-[40%] md:min-w-[40%] max-h-[50vh] min-h-[50vh]
                lg:max-w-[30%] lg:min-w-[30%] xl:max-w-[20%] xl:min-w-[20%] 2xl:max-w-[15%] 2xl:min-w-[15%] before:border-t-4 before:border-t-orange-400 before:w-[98%] before:rounded-t-md before:m-auto
                break-words overflow-hidden shadow-xl shadow-gray-400 transition-all hover:shadow-gray-700 hover:scale-105 outline-offset-2 mb-12" :class="displayClass">
        
        <!-- Contact Name -->
        <div class="rounded-t-md text-gray-700 bg-gray-300 h-1/6 max-h-1/6 text-center text-xl p-2">
            <div class="font-medium">{{ contact.name }} {{ contact.surname }}</div>
        </div>
        
        <!-- Contact Email -->
        <div class="text-gray-700 bg-gray-300 text-center h-1/6 px-2">
            <div class="text-gray-500 mt-1">({{ contact.email }})</div>
        </div>

        <!-- Contact Message -->
        <div class="rounded-none text-gray-700 bg-gray-300 h-full text my-0.5 p-2">
            <div class="text-gray-600">{{ contact.message }}</div>
        </div>

        <!-- Contact Buttons -->
        <div class="flex flex-row justify-between rounded-b-sm text-gray-300 from-sky-700 to-sky-400 bg-gradient-to-r h-1/6 bottom-0 p-2 font-bold">
            <input type="button" id="pin" value="Pin" @click="handlePin"
                class="border-white bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white border-2 rounded-md px-2 py-1 cursor-pointer" />
            
            <input type="button" id="Star" value="Star" @click="handleStar"
                class="border-white bg-cyan-500 hover:bg-cyan-600 active:bg-cyan-700 text-white border-2 rounded-md px-2 py-1 cursor-pointer" />

            <input type="button" id="Del" value="Del" @click="handleDelete"
                class="border-white bg-slate-500 hover:bg-slate-600 active:bg-slate-700 text-white border-2 rounded-md px-2 py-1 cursor-pointer" />
        </div>
    </div>
    
</template>