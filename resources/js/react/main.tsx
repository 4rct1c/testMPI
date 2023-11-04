import * as ReactDOM from 'react-dom';
import {Application} from './Components';
import axios from "axios";
/*
declare global {
    var currencies: any
    var sorting: any
    var settings: any
    var bookmarks: any
    var bells: any
    var rates: any
    var dates: any
    var range: any
    var disabled: any
}*/


ReactDOM.render(
    <Application
    />,
    document.getElementById('application')
);
