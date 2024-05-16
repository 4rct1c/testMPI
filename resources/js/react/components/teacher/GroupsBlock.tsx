import {useEffect, useState} from "react";
import {GroupBlock} from "./GroupBlock";
import axios from "axios";
import {getApiRoutes} from "../../main";


const GroupsBlock = () => {

    const [groups, setGroups] = useState([])


    const loadGroupsAxios = () => {
        return axios.get(getApiRoutes().load_groups)
    }

    useEffect(() => {
        loadGroupsAxios().then(r => {
            setGroups(r.data)
        })
    }, [])

    return <div className="box is-black theme-light">
        <h3 className="is-size-3 mb-3">
            Группы
        </h3>
        <div className="m-2">
            {groups.map(group => <GroupBlock group={group}/>)}
        </div>
    </div>
}

export {GroupsBlock}
