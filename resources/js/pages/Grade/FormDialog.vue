<template>
  <v-form @submit.prevent="saveData">
    <v-card>
      <v-card-title>
        <span class="font-bold">
          {{ isEditing ? 'Edit Kelas' : 'Tambah Kelas' }}
        </span>
      </v-card-title>

      <v-card-text>
        <v-text-field
          variant="outlined"
          v-model="form.grade_name"
          label="Nama"
          :error-messages="form.errors.grade_name"
        />
      </v-card-text>

      <v-card-actions>
        <v-spacer />
        <v-btn color="warning" variant="flat"
          @click="$emit('close-modal')">Batal</v-btn>
        <v-btn color="primary" variant="flat"
          type="submit">
          Simpan
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-form>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'

// Mendefinisikan event dan props
const emit = defineEmits(['close-modal', 'alert', 'toggle-loading'])
const props = defineProps({ formData: Object })

// Menyiapkan data reaktif
const isEditing = ref(false)

// Menyiapkan data untuk form
const form = useForm({
  id: "",
  grade_name: "",
})

// Mendeteksi perubahan props data form
watch(() => props.formData,
  (val) => {
    if (val.edit) {
      form.id = val.data.id
      form.grade_name = val.data.grade_name
      isEditing.value = true
    } else {
      form.reset()
      form.clearErrors()
      isEditing.value = false
    }
  }, { deep: true, immediate: true }
)

// Method untuk menyimpan data
const saveData = async () => {
  emit('toggle-loading', true)

  if (!isEditing.value) {
    // Tambah data baru
    form.post("/grade", {
      onSuccess: () => {
        emit('close-modal')
        emit('alert', 'Data berhasil disimpan!', 'success')
        emit('toggle-loading', false)
        form.reset()
      }
    })
  } else {
    // Edit data (menggunakan patch)
    form.patch("/grade/" + form.id, {
      onSuccess: () => {
        emit('close-modal')
        emit('alert', 'Data berhasil diperbarui!', 'success')
        emit('toggle-loading', false)
        form.reset()
      }
    })
  }
}
</script>
