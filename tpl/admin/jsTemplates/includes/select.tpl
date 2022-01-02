<v-select ${required} name="${name}" class="${classes}">
    <v-label empty="${label}"></v-label>
    <v-options>
        {foreach(object as item)}
        <v-option value="${item.value}" ${item.selected}>${item.name}</v-option>
        {/for}
    </v-options>
</v-select>
