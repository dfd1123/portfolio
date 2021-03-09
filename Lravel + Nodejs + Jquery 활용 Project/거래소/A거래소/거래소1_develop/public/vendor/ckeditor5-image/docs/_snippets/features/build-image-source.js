/**
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */

/* globals window */

import ClassicEditor from '@ckeditor/ckeditor5-build-classic/src/ckeditor';

import ImageResize from '@ckeditor/ckeditor5-image/src/imageresize';

ClassicEditor.builtinPlugins.push( ImageResize );

window.ClassicEditor = ClassicEditor;
