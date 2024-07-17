/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

import { markRaw } from 'vue';
import { VuetifyTiptap, VuetifyViewer, createVuetifyProTipTap } from 'vuetify-pro-tiptap';
import { BaseKit, Bold, Italic, Underline, Strike, Color, Highlight, Heading, TextAlign, FontFamily, FontSize, SubAndSuperScript, BulletList, OrderedList, TaskList, Indent, Link, Image, Video, Table, Blockquote, HorizontalRule, Code, CodeBlock, Clear, Fullscreen, History } from 'vuetify-pro-tiptap';
import 'vuetify-pro-tiptap/style.css';

/** @link https://github.com/yikoyu/vuetify-pro-tiptap */
export const vuetifyProTipTap = createVuetifyProTipTap({
    lang: 'en',
    components: {
        VuetifyTiptap,
        VuetifyViewer
    },
    extensions: [
        BaseKit.configure({
            placeholder: {
                placeholder: 'Enter some text...'
            }
        }),
        Bold,
        Italic,
        Underline,
        Strike,
        Code.configure({ divider: true }),
        Heading,
        TextAlign,
        FontFamily,
        FontSize,
        Color,
        Highlight.configure({ divider: true }),
        SubAndSuperScript.configure({ divider: true }),
        Clear.configure({ divider: true }),
        BulletList,
        OrderedList,
        TaskList,
        Indent.configure({ divider: true }),
        Link,
        Image,
        Video,
        Table.configure({ divider: true }),
        Blockquote,
        HorizontalRule,
        CodeBlock.configure({ divider: true }),
        History.configure({ divider: true }),
        Fullscreen
    ]
})
