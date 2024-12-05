/**
 * Form Editors
 */

'use strict';

// (function () {
//   // Full Toolbar
//   // --------------------------------------------------------------------
//   const fullToolbar = [
//     [
//       {
//         font: []
//       },
//       {
//         size: []
//       }
//     ],
//     ['bold', 'italic', 'underline', 'strike'],
//     [
//       {
//         color: []
//       },
//       {
//         background: []
//       }
//     ],
//     [
//       {
//         script: 'super'
//       },
//       {
//         script: 'sub'
//       }
//     ],
//     [
//       {
//         header: '1'
//       },
//       {
//         header: '2'
//       },
//       'blockquote',
//       'code-block'
//     ],
//     [
//       {
//         list: 'ordered'
//       },
//       {
//         list: 'bullet'
//       },
//       {
//         indent: '-1'
//       },
//       {
//         indent: '+1'
//       }
//     ],
//     [{ direction: 'rtl' }],
//     ['link', 'image', 'video', 'formula'],
//     ['clean']
//   ];
//   const fullEditor = new Quill('#full-editor', {
//     bounds: '#full-editor',
//     placeholder: 'Type Something...',
//     modules: {
//       formula: true,
//       toolbar: fullToolbar
//     },
//     theme: 'snow'
//   });
// })();

document.addEventListener('DOMContentLoaded', function() {

    if (document.getElementById('quill-editor-area')) {

        var editor = new Quill('#quill-editor', {

            theme: 'snow'

        });

        var quillEditor = document.getElementById('quill-editor-area');

        editor.on('text-change', function() {

            quillEditor.value = editor.root.innerHTML;

        });
        quillEditor.addEventListener('input', function() {

            editor.root.innerHTML = quillEditor.value;

        });

    }

});
