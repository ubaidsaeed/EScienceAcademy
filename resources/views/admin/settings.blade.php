<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Website Settings</h4>
        </div>
        <div class="card-body">

            <!-- Footer Quick Links -->
            <div class="mb-4">
                <h5>Footer Quick Links</h5>
                @foreach ($footerQuickLinks as $index => $link)
                    <div class="row mb-2">
                        <div class="col-lg-5">
                            <input type="text" class="form-control"
                                wire:model="footerQuickLinks.{{ $index }}.title" placeholder="Link Title">
                        </div>
                        <div class="col-lg-6">
                            <select class="form-control" wire:model="footerQuickLinks.{{ $index }}.slug">
                                <option selected>Link Pages...</option>
                                @foreach ($pages as $page)
                                    <option value="{{ $page->slug}}">{{ $page->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-1">
                            <button wire:click="removeFooterQuickLink({{ $index }})"
                                class="btn btn-danger">✖</button>
                        </div>
                    </div>
                @endforeach
                <button wire:click="addFooterQuickLink" class="btn btn-primary btn-sm mt-2">+ Add Link</button>
            </div>

            
            <!-- Social Links -->
            <div class="mb-4">
                <h5>Social Links</h5>
                @foreach ($socialLinks as $index => $link)
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" wire:model="socialLinks.{{ $index }}.platform"
                            placeholder="Platform (e.g., Facebook)">
                        <input type="text" class="form-control" wire:model="socialLinks.{{ $index }}.url"
                            placeholder="Profile URL">
                        <button wire:click="removeSocialLink({{ $index }})" class="btn btn-danger">✖</button>
                    </div>
                @endforeach
                <button wire:click="addSocialLink" class="btn btn-primary btn-sm mt-2">+ Add Social Link</button>
            </div>

            <!-- WhatsApp Contact -->
            <div class="row">
                <div class="mb-4 col-lg-6">
                    <h5>WhatsApp Contact</h5>
                    <input type="text" class="form-control" wire:model="whatsappContact"
                        placeholder="Enter WhatsApp Contact">
                </div>
                <div class="mb-4 col-lg-6">
                    <h5>Contact Mail</h5>
                    <input type="email" class="form-control" wire:model="contact_mail"
                        placeholder="Enter Contact Mail">
                </div>
            </div>
            <!-- site address -->
            <div class="mb-4">
                <h5>Address</h5>
                <textarea class="form-control" wire:model="address" placeholder="Enter Address of your site"></textarea>
            </div>
            <div class="mb-4">
                <h5>Site Title</h5>
                <input type="text" class="form-control" wire:model="site_title"
                    placeholder="Enter Title of your site">
            </div>
            <div class="mb-4">
                <div x-data x-init="$nextTick(() => {
                                    function initTinyMCE() {
                                        if (tinymce.get('myeditorinstance')) {
                                            tinymce.get('myeditorinstance').remove();
                                        }
                                        tinymce.init({
                                            selector: '#myeditorinstance',
                                            height: 300,
                                            plugins: 'image code table link media codesample',
                                            toolbar: 'undo redo | styleselect | bold italic | image | alignleft aligncenter alignright alignjustify | outdent indent | media | table',
                                            image_title: true,
                                            automatic_uploads: true,
                                            file_picker_types: 'image',
                                            file_picker_callback: function (cb, value, meta) {
                                                const input = document.createElement('input');
                                                input.setAttribute('type', 'file');
                                                input.setAttribute('accept', 'image/*');
                                                input.onchange = function () {
                                                    const file = this.files[0];
                                                    const reader = new FileReader();
                                                    reader.onload = function () {
                                                        const id = 'blobid' + (new Date()).getTime();
                                                        const blobCache = tinymce.activeEditor.editorUpload.blobCache;
                                                        const base64 = reader.result.split(',')[1];
                                                        const blobInfo = blobCache.create(id, file, base64);
                                                        blobCache.add(blobInfo);
                                                        cb(blobInfo.blobUri(), { title: file.name });
                                                    };
                                                    reader.readAsDataURL(file);
                                                };
                                                input.click();
                                            },
                                            setup: function (editor) {
                                                editor.on('change', function () {
                                                    @this.set('site_description', editor.getContent());
                                                });
                                            }
                                        });
                                    }

                                    initTinyMCE();

                                    Livewire.hook('message.processed', () => {
                                        initTinyMCE();
                                        const editor = tinymce.get('myeditorinstance');
                                        if (editor) {
                                            editor.setContent(@this.get('site_description') || '');
                                        }
                                    });
                                })" wire:ignore>
                <h5>Site Description</h5>
                <textarea class="form-control" id="myeditorinstance" x-ref="myeditorinstance" wire:model="site_description" placeholder="Enter Description of your site"></textarea>
                </div>
            </div>
            <div class="row">

                <div class="mb-4 col-lg-6" wire:ignore>
                    <h5>Site Logo</h5>
                    <input type="file" class="dropify" data-default-file="{{ $site_logo ? asset('storage/site-logo/' . $site_logo) : '' }}" data-height="180" wire:model="site_logo" wire:ignore>

                </div>
                <div class="mb-4 col-lg-6" wire:ignore>
                    <h5>Favicon</h5>
                    <input type="file" class="dropify" data-default-file="{{ $favicon ? asset('storage/favicon/' . $favicon) : '' }}" data-height="180" wire:model="favicon" wire:ignore>
                </div>
            </div>


            <!-- Save Button -->
            <button wire:click="saveSettings" class="btn btn-success w-100">Save Settings</button>

        </div>
    </div>
</div>

@push('script')
    <script src="{{ asset('build/assets/admin/js/custom/message.js') }}"></script>
@endpush
