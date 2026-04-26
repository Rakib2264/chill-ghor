{{-- Advertisement Form Fields (shared by create & edit) --}}

<div class="grid gap-4 sm:grid-cols-2">

  {{-- Title --}}
  <div class="sm:col-span-2">
    <label class="block text-xs font-bold text-gray-600">টাইটেল <span class="text-red-500">*</span></label>
    <input type="text" name="title" :value="form.title" @input="form.title = $event.target.value"
      required placeholder="যেমন: ফুচকা খাওয়ার প্রতিযোগিতা চলছে!"
      class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none">
  </div>

  {{-- Body --}}
  <div class="sm:col-span-2">
    <label class="block text-xs font-bold text-gray-600">বিস্তারিত বার্তা <span class="text-red-500">*</span></label>
    <textarea name="body" :value="form.body" @input="form.body = $event.target.value"
      required rows="3" placeholder="যেমন: আজ বিকেল ৪টায় চিল ঘরে ৫ মিনিটের ফুচকা খাওয়ার প্রতিযোগিতা। বিজয়ীর জন্য বিশেষ পুরস্কার!"
      class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none"></textarea>
  </div>

  {{-- Emoji --}}
  <div>
    <label class="block text-xs font-bold text-gray-600">ইমোজি</label>
    <input type="text" name="emoji" :value="form.emoji" @input="form.emoji = $event.target.value"
      placeholder="🎉" maxlength="10"
      class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none">
  </div>

  {{-- Badge --}}
  <div>
    <label class="block text-xs font-bold text-gray-600">ব্যাজ টেক্সট <span class="text-gray-400 font-normal">(ঐচ্ছিক)</span></label>
    <input type="text" name="badge" :value="form.badge" @input="form.badge = $event.target.value"
      placeholder="যেমন: 🔥 আজকের অফার"
      class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none">
  </div>

  {{-- BG Color --}}
  <div>
    <label class="block text-xs font-bold text-gray-600">ব্যাকগ্রাউন্ড রঙ</label>
    <div class="mt-1 flex items-center gap-2">
      <input type="color" name="bg_color" :value="form.bg_color" @input="form.bg_color = $event.target.value"
        class="h-10 w-14 cursor-pointer rounded-lg border border-charcoal/15">
      <input type="text" :value="form.bg_color" @input="form.bg_color = $event.target.value"
        class="flex-1 rounded-xl border border-charcoal/15 bg-cream px-3 py-2.5 text-sm focus:outline-none">
    </div>
  </div>

  {{-- Text Color --}}
  <div>
    <label class="block text-xs font-bold text-gray-600">টেক্সট রঙ</label>
    <div class="mt-1 flex items-center gap-2">
      <input type="color" name="text_color" :value="form.text_color" @input="form.text_color = $event.target.value"
        class="h-10 w-14 cursor-pointer rounded-lg border border-charcoal/15">
      <input type="text" :value="form.text_color" @input="form.text_color = $event.target.value"
        class="flex-1 rounded-xl border border-charcoal/15 bg-cream px-3 py-2.5 text-sm focus:outline-none">
    </div>
  </div>

  {{-- CTA Text --}}
  <div>
    <label class="block text-xs font-bold text-gray-600">বাটন টেক্সট <span class="text-gray-400 font-normal">(ঐচ্ছিক)</span></label>
    <input type="text" name="cta_text" :value="form.cta_text" @input="form.cta_text = $event.target.value"
      placeholder="যেমন: এখনই আসুন →"
      class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none">
  </div>

  {{-- CTA URL --}}
  <div>
    <label class="block text-xs font-bold text-gray-600">বাটন লিংক</label>
    <input type="text" name="cta_url" :value="form.cta_url" @input="form.cta_url = $event.target.value"
      placeholder="/menu বা https://..."
      class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none">
  </div>

  {{-- CTA Color --}}
  <div>
    <label class="block text-xs font-bold text-gray-600">বাটন রঙ</label>
    <div class="mt-1 flex items-center gap-2">
      <input type="color" name="cta_color" :value="form.cta_color" @input="form.cta_color = $event.target.value"
        class="h-10 w-14 cursor-pointer rounded-lg border border-charcoal/15">
      <input type="text" :value="form.cta_color" @input="form.cta_color = $event.target.value"
        class="flex-1 rounded-xl border border-charcoal/15 bg-cream px-3 py-2.5 text-sm focus:outline-none">
    </div>
  </div>

  {{-- Style --}}
  <div>
    <label class="block text-xs font-bold text-gray-600">ধরন</label>
    <select name="style" :value="form.style" @change="form.style = $event.target.value"
      class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none">
      <option value="popup">🪟 পপআপ (মাঝখানে)</option>
      <option value="banner">📌 ব্যানার (সেকশনের মাঝে)</option>
      <option value="slide">📲 স্লাইড (নিচে কোণে)</option>
    </select>
  </div>

  {{-- Pages --}}
  <div class="sm:col-span-2">
    <label class="block text-xs font-bold text-gray-600 mb-2">কোন পেজে দেখাবে</label>
    <div class="flex flex-wrap gap-2">
      @foreach(['all' => 'সব পেজ', 'home' => 'হোম পেজ', 'menu' => 'মেনু পেজ'] as $val => $label)
        <label class="inline-flex cursor-pointer items-center gap-2 rounded-xl border px-4 py-2.5 text-sm font-semibold transition"
          :class="form.show_on_pages?.includes('{{ $val }}') ? 'border-primary bg-primary/5 text-primary' : 'border-charcoal/15 text-charcoal/70'">
          <input type="checkbox" name="show_on_pages[]" value="{{ $val }}"
            :checked="form.show_on_pages?.includes('{{ $val }}')"
            @change="
              const v = '{{ $val }}';
              if ($event.target.checked) {
                if (v === 'all') form.show_on_pages = ['all'];
                else { form.show_on_pages = form.show_on_pages.filter(p => p !== 'all'); form.show_on_pages.push(v); }
              } else {
                form.show_on_pages = form.show_on_pages.filter(p => p !== v);
              }
            "
            class="accent-primary">
          {{ $label }}
        </label>
      @endforeach
    </div>
  </div>

  {{-- Schedule --}}
  <div>
    <label class="block text-xs font-bold text-gray-600">শুরুর তারিখ <span class="text-gray-400 font-normal">(ঐচ্ছিক)</span></label>
    <input type="datetime-local" name="starts_at" :value="form.starts_at" @input="form.starts_at = $event.target.value"
      class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none">
  </div>

  <div>
    <label class="block text-xs font-bold text-gray-600">শেষের তারিখ <span class="text-gray-400 font-normal">(ঐচ্ছিক)</span></label>
    <input type="datetime-local" name="ends_at" :value="form.ends_at" @input="form.ends_at = $event.target.value"
      class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none">
  </div>

  {{-- Sort order + Active --}}
  <div>
    <label class="block text-xs font-bold text-gray-600">ক্রম নম্বর</label>
    <input type="number" name="sort_order" :value="form.sort_order" @input="form.sort_order = $event.target.value"
      min="0" class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none">
  </div>

  <div class="flex items-center">
    <label class="inline-flex cursor-pointer items-center gap-3 rounded-xl border border-charcoal/15 bg-cream px-4 py-3 w-full">
      <input type="hidden" name="is_active" value="0">
      <input type="checkbox" name="is_active" value="1" :checked="form.is_active" @change="form.is_active = $event.target.checked"
        class="h-5 w-5 accent-primary">
      <div>
        <div class="text-sm font-bold">এখনই সক্রিয় করুন</div>
        <div class="text-xs text-gray-500">চেক করলে সাথে সাথে দেখাবে</div>
      </div>
    </label>
  </div>
</div>

{{-- Preview --}}
<div class="rounded-xl border border-charcoal/10 overflow-hidden">
  <div class="bg-gray-50 px-4 py-2 text-xs font-bold text-gray-500">👁️ প্রিভিউ</div>
  <div class="relative overflow-hidden px-5 py-4" :style="`background:${form.bg_color};color:${form.text_color}`">
    <div class="flex items-start gap-3">
      <span class="text-3xl leading-none" x-text="form.emoji || '🎉'"></span>
      <div>
        <template x-if="form.badge">
          <span class="mb-1 inline-block rounded-full px-2 py-0.5 text-xs font-bold" style="background:rgba(255,255,255,.2)" x-text="form.badge"></span>
        </template>
        <div class="font-bold text-sm" x-text="form.title || 'টাইটেল লিখুন...'"></div>
        <div class="text-xs opacity-75 mt-0.5" x-text="form.body || 'বিস্তারিত লিখুন...'"></div>
        <template x-if="form.cta_text">
          <div class="mt-2 inline-block rounded-full px-3 py-1.5 text-xs font-bold"
            :style="`background:${form.cta_color};color:${form.bg_color}`"
            x-text="form.cta_text"></div>
        </template>
      </div>
    </div>
  </div>
</div>

{{-- Submit --}}
<div class="flex justify-end gap-3">
  <button type="button" @click="showForm = false; resetForm()"
    class="rounded-full border border-charcoal/20 px-6 py-2.5 text-sm font-bold text-charcoal/70 transition hover:bg-charcoal/5">
    বাতিল
  </button>
  <button type="submit"
    class="inline-flex items-center gap-2 rounded-full bg-gradient-warm px-7 py-2.5 text-sm font-bold text-white shadow-warm transition hover:scale-105">
    <span x-text="editMode ? '💾 আপডেট করুন' : '✅ তৈরি করুন'"></span>
  </button>
</div>