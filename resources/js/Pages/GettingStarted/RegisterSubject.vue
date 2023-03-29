<template>
    <div>
        <Heading value="Batch subject page"></Heading>
        <FormElement>
            <div  class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            <Card v-for="(subject, index) in subjects" :key="index">
                <div>
                    <LightBulbIcon class="h-9 w-9"></LightBulbIcon>
                </div>
                <!-- Card content here -->
                <p>{{subject.name}}</p>
            </Card>
            </div>
        </FormElement>
    </div>
</template>


<script setup>
import {ref} from "vue"
import FormElement from "@/Components/FormElement.vue";
import Card from "@/Components/Card.vue";
import Heading from "@/Components/Heading.vue";
import {LightBulbIcon} from '@heroicons/vue/24/outline';

const subjects = ref([
    {id:1, name:'Biology',},
    {id:1, name:'Chemistry'},
    {id:1, name:'Physics'},
    ]
)
</script>
