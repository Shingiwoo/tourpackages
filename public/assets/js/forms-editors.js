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

document.addEventListener("DOMContentLoaded", function () {
    // Inisialisasi Quill Editor
    var quill = new Quill("#quill-editor", {
        theme: "snow",
        placeholder: "Write something...",
        modules: {
            toolbar: [
                    [
                      {
                        font: []
                      },
                      {
                        size: []
                      }
                    ],
                    ['bold', 'italic', 'underline', 'strike'],
                    [
                      {
                        color: []
                      },
                      {
                        background: []
                      }
                    ],
                    [
                      {
                        script: 'super'
                      },
                      {
                        script: 'sub'
                      }
                    ],
                    [
                      {
                        header: '1'
                      },
                      {
                        header: '2'
                      },
                      'blockquote',
                      'code-block'
                    ],
                    [
                      {
                        list: 'ordered'
                      },
                      {
                        list: 'bullet'
                      },
                      {
                        indent: '-1'
                      },
                      {
                        indent: '+1'
                      }
                    ],
                    [{ direction: 'rtl' }],
                    ['link', 'image', 'video', 'formula'],
                    ['clean']
                  ],
        },
    });

    // Ambil data dari textarea dan masukkan ke Quill
    const quillEditorArea = document.getElementById("quill-editor-area");
    const existingContent = quillEditorArea.value.trim();
    quill.root.innerHTML = existingContent;

    // Sinkronkan kembali ke textarea sebelum submit
    quill.on("text-change", function () {
        quillEditorArea.value = quill.root.innerHTML;
    });
});

