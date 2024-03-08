import Layout from "@/views/layouts/main";
import {Metadata} from "next";
import View from '@/views/project/upload';

export const metadata: Metadata = {
    title: 'Upload'
};

const Page = async () => {
    const props = {

    }

    return (
        <Layout>
            <View />
        </Layout>
    )
}

export default Page;
